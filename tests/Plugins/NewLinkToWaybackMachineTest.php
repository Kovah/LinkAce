<?php

namespace Tests\Plugins;

use App\Jobs\SaveLinkToWaybackmachine;
use App\Settings\UserSettings;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NewLinkToWaybackMachineTest extends TestCase
{
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
