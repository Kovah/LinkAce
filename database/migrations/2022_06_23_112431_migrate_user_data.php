<?php

use App\Enums\ModelAttribute;
use App\Enums\Role;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateUserData extends Migration
{
    private bool $guestAccessEnabled;

    public function up()
    {
        $this->guestAccessEnabled = systemsettings('guest_access_enabled');

        $this->migrateLinkVisibility();
        $this->migrateListVisibility();
        $this->migrateTagVisibility();
        $this->migrateNoteVisibility();

        $this->addUserRoles();
    }

    protected function migrateLinkVisibility(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->integer('visibility')->default(ModelAttribute::VISIBILITY_PRIVATE)->after('is_private');
        });

        Link::withTrashed()->chunk(500, function ($links) {
            foreach ($links as $link) {
                $link->visibility = match ((bool)$link->is_private) {
                    true => ModelAttribute::VISIBILITY_PRIVATE,
                    false => $this->guestAccessEnabled
                        ? ModelAttribute::VISIBILITY_PUBLIC : ModelAttribute::VISIBILITY_INTERNAL,
                };
                $link->saveQuietly();
            }
        });

        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn(['is_private']);
        });
    }

    protected function migrateListVisibility(): void
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->integer('visibility')->default(ModelAttribute::VISIBILITY_PRIVATE)->after('is_private');
        });

        LinkList::withTrashed()->get()->each(function ($list) {
            $list->visibility = match ((bool)$list->is_private) {
                true => ModelAttribute::VISIBILITY_PRIVATE,
                false => $this->guestAccessEnabled
                    ? ModelAttribute::VISIBILITY_PUBLIC : ModelAttribute::VISIBILITY_INTERNAL,
            };
            $list->saveQuietly();
        });

        Schema::table('lists', function (Blueprint $table) {
            $table->dropColumn(['is_private']);
        });
    }

    protected function migrateTagVisibility(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->integer('visibility')->default(ModelAttribute::VISIBILITY_PRIVATE)->after('is_private');
        });

        Tag::withTrashed()->get()->each(function ($tag) {
            $tag->visibility = match ((bool)$tag->is_private) {
                true => ModelAttribute::VISIBILITY_PRIVATE,
                false => $this->guestAccessEnabled
                    ? ModelAttribute::VISIBILITY_PUBLIC : ModelAttribute::VISIBILITY_INTERNAL,
            };
            $tag->saveQuietly();
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn(['is_private']);
        });
    }

    protected function migrateNoteVisibility(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->integer('visibility')->default(ModelAttribute::VISIBILITY_PRIVATE)->after('is_private');
        });

        Note::withTrashed()->get()->each(function ($note) {
            $note->visibility = match ((bool)$note->is_private) {
                true => ModelAttribute::VISIBILITY_PRIVATE,
                false => $this->guestAccessEnabled
                    ? ModelAttribute::VISIBILITY_PUBLIC : ModelAttribute::VISIBILITY_INTERNAL,
            };
            $note->saveQuietly();
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['is_private']);
        });
    }

    protected function addUserRoles(): void
    {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);

        $newAdmin = User::first();
        if (!$newAdmin->hasRole(Role::ADMIN)) {
            $newAdmin->assignRole(Role::ADMIN);
        }
    }
}
