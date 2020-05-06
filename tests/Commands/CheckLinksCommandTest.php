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

        $this->user = factory(User::class)->create();
    }

    public function testCheckWithHealthyLinks(): void
    {
        Http::fake([
            '*' => Http::response('', 200),
        ]);

        Notification::fake();

        factory(Link::class)->create();

        $this->artisan('links:check');

        Notification::assertNothingSent();
    }

    public function testCheckWithMovedLinks(): void
    {
        Http::fake([
            '*' => Http::response('', 302),
        ]);

        Notification::fake();

        factory(Link::class)->create();

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

        factory(Link::class)->create();

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

        factory(Link::class, 10)->create();

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
}
