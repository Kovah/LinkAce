<?php

namespace Tests\Commands;

use App\Models\Link;
use App\Models\User;
use App\Notifications\LinkCheckNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CheckLinksCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_with200_response(): void
    {
        Http::fake([
            '*' => Http::response(),
        ]);

        Notification::fake();

        Link::factory()->create();

        $this->artisan('links:check');

        Notification::assertNothingSent();
    }

    public function test_check_with204_response(): void
    {
        Http::fake([
            '*' => Http::response(status: 204),
        ]);

        Notification::fake();

        Link::factory()->create();

        $this->artisan('links:check');

        Notification::assertNothingSent();
    }

    public function test_check_with_moved_or_broken_links(): void
    {
        // example.com/example.net are used here (instead of made-up domains)
        // because they are reserved by RFC 2606 and always resolve via DNS;
        // block_private_ips (enabled by default) now fails closed on hosts
        // that cannot be resolved.
        Http::fake([
            'example.com/okay' => Http::response(),
            'example.com/moved' => Http::response(status: 300),
            'example.com/failed' => Http::response(status: 503),
            'example.net/okay' => Http::response(),
            'example.net/moved1' => Http::response(status: 302),
            'example.net/moved2' => Http::response(status: 302),
            'example.net/failed1' => Http::response(status: 503),
            'example.net/failed2' => Http::response(status: 503),
        ]);

        Notification::fake();

        $user = User::factory()->create();
        Link::factory()->for($user)->create(['url' => 'https://example.com/okay']);
        Link::factory()->for($user)->create(['url' => 'https://example.com/moved']);
        Link::factory()->for($user)->create(['url' => 'https://example.com/failed']);

        $anotherUser = User::factory()->create();
        Link::factory()->for($anotherUser)->create(['url' => 'https://example.net/okay']);
        Link::factory()->for($anotherUser)->create(['url' => 'https://example.net/moved1']);
        Link::factory()->for($anotherUser)->create(['url' => 'https://example.net/moved2']);
        Link::factory()->for($anotherUser)->create(['url' => 'https://example.net/failed1']);
        Link::factory()->for($anotherUser)->create(['url' => 'https://example.net/failed2']);

        $this->artisan('links:check');

        Notification::assertSentTo(
            $user,
            LinkCheckNotification::class,
            fn (LinkCheckNotification $notification) => count($notification->movedLinks) === 1
                && count($notification->brokenLinks) === 1
        );

        Notification::assertSentTo(
            $anotherUser,
            LinkCheckNotification::class,
            fn (LinkCheckNotification $notification) => count($notification->movedLinks) === 2
                && count($notification->brokenLinks) === 2
        );
    }

    public function test_check_skips_private_ip_links(): void
    {
        Http::fake();
        Notification::fake();

        $user = User::factory()->create();
        Link::factory()->for($user)->create(['url' => 'http://192.168.1.1/']);
        Link::factory()->for($user)->create(['url' => 'http://127.0.0.1/']);
        Link::factory()->for($user)->create(['url' => 'http://169.254.169.254/latest/meta-data/']);
        Link::factory()->for($user)->create(['url' => 'http://[::1]/']);

        config(['html-meta.block_private_ips' => true]);

        $this->artisan('links:check --noWait');

        Http::assertNothingSent();
        Notification::assertNothingSent();

        // Status and last_checked_at should remain untouched
        $this->assertDatabaseMissing('links', ['status' => Link::STATUS_BROKEN]);
        $this->assertDatabaseMissing('links', ['last_checked_at' => now()]);
    }

    /**
     * Regression test for GHSA-x8w7-mhjm-xvj2. CheckLinksCommand now delegates
     * private-IP protection to Kovah\HtmlMeta\HtmlMeta::applyPrivateIpProtection()
     * instead of maintaining its own divergent copy of the resolution logic.
     * When DNS resolution returns no records at all, the request must be
     * rejected (fail closed) instead of allowed through.
     */
    public function test_check_fails_closed_when_dns_resolution_returns_no_records(): void
    {
        require_once __DIR__ . '/../Support/DnsInterceptStub.php';
        \Tests\Support\FakeDns::reset();

        Http::fake(['*' => Http::response()]);
        Notification::fake();

        $user = User::factory()->create();
        Link::factory()->for($user)->create(['url' => 'http://unresolvable-internal.invalid']);

        config(['html-meta.block_private_ips' => true]);

        $this->artisan('links:check --noWait');

        // Mirrors test_check_skips_private_ip_links: an unresolvable host must
        // be skipped, not silently allowed through.
        Http::assertNothingSent();
    }

    public function test_check_allows_private_ip_links_when_config_disabled(): void
    {
        Http::fake(['*' => Http::response()]);
        Notification::fake();

        $user = User::factory()->create();
        Link::factory()->for($user)->create(['url' => 'http://192.168.1.1/']);

        config(['html-meta.block_private_ips' => false]);

        $this->artisan('links:check --noWait');

        Http::assertSentCount(1);
    }

    public function test_check_without_links(): void
    {
        Notification::fake();

        $this->artisan('links:check');

        Notification::assertNothingSent();
    }

    public function test_check_with_exception(): void
    {
        Http::fake(function () {
            throw new ConnectionException(
                'cURL error 7: Failed to connect to 192.168.0.123 port 54623: Connection refused'
            );
        });

        Notification::fake();

        $user = User::factory()->create();
        Link::factory()->for($user)->create();

        $this->artisan('links:check');

        Notification::assertSentTo(
            $user,
            LinkCheckNotification::class,
            fn (LinkCheckNotification $notification) => count($notification->brokenLinks) === 1
        );
    }

    public function test_check_with_limit(): void
    {
        Http::fake([
            '*' => Http::response(status: 404),
        ]);

        Notification::fake();

        $user = User::factory()->create();
        Link::factory()->for($user)->count(10)->create();

        $this->artisan('links:check', ['--limit' => 5]);

        Notification::assertSentTo(
            $user,
            LinkCheckNotification::class,
            fn (LinkCheckNotification $notification) => count($notification->brokenLinks) === 5
        );
    }

    public function test_broken_link_is_rechecked_after_two_weeks(): void
    {
        Http::fake(['*' => Http::response()]);
        Notification::fake();

        $user = User::factory()->create();
        Link::factory()->for($user)->create([
            'status' => Link::STATUS_BROKEN,
            'last_checked_at' => now()->subWeeks(3),
        ]);

        $this->artisan('links:check --noWait');

        $this->assertDatabaseHas('links', ['status' => Link::STATUS_OK]);
    }

    public function test_broken_link_is_not_rechecked_before_two_weeks(): void
    {
        Http::fake(['*' => Http::response()]);
        Notification::fake();

        $user = User::factory()->create();
        Link::factory()->for($user)->create([
            'status' => Link::STATUS_BROKEN,
            'last_checked_at' => now()->subWeek(),
        ]);

        $this->artisan('links:check --noWait');

        // Link was checked too recently — should remain broken (not re-checked)
        $this->assertDatabaseHas('links', ['status' => Link::STATUS_BROKEN]);
    }

    public function test_broken_link_recheck_interval_is_configurable(): void
    {
        Http::fake(['*' => Http::response()]);
        Notification::fake();

        config(['linkace.link_checks.broken_recheck_interval_weeks' => 4]);

        $user = User::factory()->create();
        Link::factory()->for($user)->create([
            'status' => Link::STATUS_BROKEN,
            'last_checked_at' => now()->subWeeks(3),
        ]);

        $this->artisan('links:check --noWait');

        // 3 weeks old but interval is 4 — should NOT be re-checked yet
        $this->assertDatabaseHas('links', ['status' => Link::STATUS_BROKEN]);
    }
}
