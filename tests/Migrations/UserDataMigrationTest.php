<?php

namespace Tests\Migrations;

use App\Models\Link;
use App\Settings\SystemSettings;
use Tests\TestCase;

class UserDataMigrationTest extends TestCase
{
    use MigratesUpTo;

    public function testLinkVisibilityMigration(): void
    {
        $this->migrateUpTo('2022_06_23_112431_migrate_user_data.php');

        Link::unguard();
        Link::create([
            'url' => 'https://private-link.com',
            'title' => 'Test',
            'user_id' => 1,
            'is_private' => true,
        ]);

        Link::create([
            'url' => 'https://public-link.com',
            'title' => 'Test',
            'user_id' => 1,
            'is_private' => false,
        ]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('links', [
            'url' => 'https://private-link.com',
            'visibility' => 3, // is private
        ]);
        $this->assertDatabaseHas('links', [
            'url' => 'https://public-link.com',
            'visibility' => 2, // is internal
        ]);
    }

    public function testLinkVisibilityMigrationWithEnabledGuestMode(): void
    {
        $this->migrateUpTo('2022_06_23_112431_migrate_user_data.php');

        SystemSettings::fake(['guest_access_enabled' => true]);

        Link::unguard();
        Link::create([
            'url' => 'https://private-link.com',
            'title' => 'Test',
            'user_id' => 1,
            'is_private' => true,
        ]);

        Link::create([
            'url' => 'https://public-link.com',
            'title' => 'Test',
            'user_id' => 1,
            'is_private' => false,
        ]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('links', [
            'url' => 'https://private-link.com',
            'visibility' => 3, // is private
        ]);
        $this->assertDatabaseHas('links', [
            'url' => 'https://public-link.com',
            'visibility' => 1, // is public
        ]);
    }
}
