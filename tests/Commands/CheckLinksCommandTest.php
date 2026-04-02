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
        Http::fake([
            'example.com/okay' => Http::response(),
            'example.com/moved' => Http::response(status: 300),
            'example.com/failed' => Http::response(status: 503),
            'test.com/okay' => Http::response(),
            'test.com/moved1' => Http::response(status: 302),
            'test.com/moved2' => Http::response(status: 302),
            'test.com/failed1' => Http::response(status: 503),
            'test.com/failed2' => Http::response(status: 503),
        ]);

        Notification::fake();

        $user = User::factory()->create();
        Link::factory()->for($user)->create(['url' => 'https://example.com/okay']);
        Link::factory()->for($user)->create(['url' => 'https://example.com/moved']);
        Link::factory()->for($user)->create(['url' => 'https://example.com/failed']);

        $anotherUser = User::factory()->create();
        Link::factory()->for($anotherUser)->create(['url' => 'https://test.com/okay']);
        Link::factory()->for($anotherUser)->create(['url' => 'https://test.com/moved1']);
        Link::factory()->for($anotherUser)->create(['url' => 'https://test.com/moved2']);
        Link::factory()->for($anotherUser)->create(['url' => 'https://test.com/failed1']);
        Link::factory()->for($anotherUser)->create(['url' => 'https://test.com/failed2']);

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
}
