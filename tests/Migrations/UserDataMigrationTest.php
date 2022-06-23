<?php

namespace Tests\Migrations;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
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

    public function testListVisibilityMigration(): void
    {
        $this->migrateUpTo('2022_06_23_112431_migrate_user_data.php');

        LinkList::unguard();
        LinkList::create([
            'name' => 'Private List',
            'user_id' => 1,
            'is_private' => true,
        ]);

        LinkList::create([
            'name' => 'Public List',
            'user_id' => 1,
            'is_private' => false,
        ]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('lists', [
            'name' => 'Private List',
            'visibility' => 3, // is private
        ]);
        $this->assertDatabaseHas('lists', [
            'name' => 'Public List',
            'visibility' => 2, // is internal
        ]);
    }

    public function testListVisibilityMigrationWithEnabledGuestMode(): void
    {
        $this->migrateUpTo('2022_06_23_112431_migrate_user_data.php');

        SystemSettings::fake(['guest_access_enabled' => true]);

        LinkList::unguard();
        LinkList::create([
            'name' => 'Private List',
            'user_id' => 1,
            'is_private' => true,
        ]);

        LinkList::create([
            'name' => 'Public List',
            'user_id' => 1,
            'is_private' => false,
        ]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('lists', [
            'name' => 'Private List',
            'visibility' => 3, // is private
        ]);
        $this->assertDatabaseHas('lists', [
            'name' => 'Public List',
            'visibility' => 1, // is public
        ]);
    }

    public function testTagVisibilityMigration(): void
    {
        $this->migrateUpTo('2022_06_23_112431_migrate_user_data.php');

        Tag::unguard();
        Tag::create([
            'name' => 'PrivateTag',
            'user_id' => 1,
            'is_private' => true,
        ]);

        Tag::create([
            'name' => 'PublicTag',
            'user_id' => 1,
            'is_private' => false,
        ]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('tags', [
            'name' => 'PrivateTag',
            'visibility' => 3, // is private
        ]);
        $this->assertDatabaseHas('tags', [
            'name' => 'PublicTag',
            'visibility' => 2, // is internal
        ]);
    }

    public function testTagVisibilityMigrationWithEnabledGuestMode(): void
    {
        $this->migrateUpTo('2022_06_23_112431_migrate_user_data.php');

        SystemSettings::fake(['guest_access_enabled' => true]);

        Tag::unguard();
        Tag::create([
            'name' => 'PrivateTag',
            'user_id' => 1,
            'is_private' => true,
        ]);

        Tag::create([
            'name' => 'PublicTag',
            'user_id' => 1,
            'is_private' => false,
        ]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('tags', [
            'name' => 'PrivateTag',
            'visibility' => 3, // is private
        ]);
        $this->assertDatabaseHas('tags', [
            'name' => 'PublicTag',
            'visibility' => 1, // is public
        ]);
    }
}
