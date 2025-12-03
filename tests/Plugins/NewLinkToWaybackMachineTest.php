<?php

namespace Tests\Plugins;

use App\Jobs\SaveLinkToWaybackmachine;
use App\Models\User;
use App\Settings\UserSettings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NewLinkToWaybackMachineTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);

        Http::preventStrayRequests();
        Http::fake([
            'example.com' => Http::response('ok'),
        ]);

        Queue::fake();
        Config::set('linkace.plugins', [\App\Plugins\NewLinkToWaybackMachine::class]);
    }

    public function test_store_request_archives_links(): void
    {
        UserSettings::fake([
            'archive_backups_enabled' => true,
            'archive_private_backups_enabled' => false,
        ]);

        $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ]);

        Queue::assertPushed(SaveLinkToWaybackmachine::class);
    }

    public function test_store_request_doesnt_archive_without_being_enabled(): void
    {
        UserSettings::fake([
            'archive_backups_enabled' => false,
        ]);

        $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'visibility' => 1,
        ]);

        Queue::assertNotPushed(SaveLinkToWaybackmachine::class);
    }

    public function test_store_request_doesnt_archive_private_links(): void
    {
        UserSettings::fake([
            'archive_backups_enabled' => true,
            'archive_private_backups_enabled' => false,
        ]);

        $this->post('links', [
            'url' => 'https://example.com',
            'title' => null,
            'description' => null,
            'lists' => null,
            'tags' => null,
            'visibility' => 3,
        ]);

        Queue::assertNotPushed(SaveLinkToWaybackmachine::class);
    }
}
