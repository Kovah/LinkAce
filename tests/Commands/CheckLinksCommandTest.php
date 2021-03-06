<?php

namespace Tests\Commands;

use App\Models\Link;
use App\Models\User;
use App\Notifications\LinkCheckNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CheckLinksCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testCheckWith200Response(): void
    {
        Http::fake([
            '*' => Http::response('', 200),
        ]);

        Notification::fake();

        Link::factory()->create();

        $this->artisan('links:check');

        Notification::assertNothingSent();
    }

    public function testCheckWith204Response(): void
    {
        Http::fake([
            '*' => Http::response('', 204),
        ]);

        Notification::fake();

        Link::factory()->create();

        $this->artisan('links:check');

        Notification::assertNothingSent();
    }

    public function testCheckWithMovedLinks(): void
    {
        Http::fake([
            '*' => Http::response('', 302),
        ]);

        Notification::fake();

        Link::factory()->create();

        $this->artisan('links:check');

        Notification::assertSentTo(
            $this->user,
            LinkCheckNotification::class,
            function (LinkCheckNotification $notification, $channels) {
                return count($notification->movedLinks) === 1;
            }
        );
    }

    public function testCheckWithBrokenLinks(): void
    {
        Http::fake([
            '*' => Http::response('', 500),
        ]);

        Notification::fake();

        Link::factory()->create();

        $this->artisan('links:check');

        Notification::assertSentTo(
            $this->user,
            LinkCheckNotification::class,
            function (LinkCheckNotification $notification, $channels) {
                return count($notification->brokenLinks) === 1;
            }
        );
    }

    public function testCheckWithOffset(): void
    {
        Http::fake([
            '*' => Http::response('', 500),
        ]);

        Notification::fake();

        Link::factory()->count(10)->create();

        $this->artisan('links:check', [
            '--limit' => 5,
            '--noWait' => true,
        ]);

        $this->assertEquals(5, cache()->get('command_links:check_checked_count'));

        $this->artisan('links:check', [
            '--limit' => 5,
            '--noWait' => true,
        ]);

        $this->assertEquals(10, cache()->get('command_links:check_checked_count'));
    }

    public function testCheckWithoutLinks(): void
    {
        $this->artisan('links:check');

        $this->assertEquals(null, cache()->get('command_links:check_offset'));
        $this->assertEquals(null, cache()->get('command_links:check_skip_timestamp'));
    }
}
