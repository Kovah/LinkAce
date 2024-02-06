<?php

use App\Enums\ApiToken;
use App\Enums\ModelAttribute;
use App\Enums\Role;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private bool $guestAccessEnabled = false;

    public function up()
    {
        $this->guestAccessEnabled = systemsettings('guest_access_enabled');

        $this->migrateLinkVisibility();
        $this->migrateListVisibility();
        $this->migrateTagVisibility();
        $this->migrateNoteVisibility();

        $this->addUserRoles();
        $this->migrateApiTokens();
        $this->createSystemUser();
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

        LinkList::withTrashed()->get()->each(function (LinkList $list) {
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

        Tag::withTrashed()->get()->each(function (Tag $tag) {
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

        Note::withTrashed()->get()->each(function (Note $note) {
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
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder', '--force' => true]);

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('blocked_at')->nullable()->after('api_token');
            $table->softDeletes();
        });

        $newAdmin = User::first();
        if ($newAdmin !== null && !$newAdmin->hasRole(Role::ADMIN)) {
            $newAdmin->assignRole(Role::ADMIN);
        }
    }

    public function migrateApiTokens(): void
    {
        User::all()->each(function (User $user) {
            $user->tokens()->create([
                'name' => 'MigratedApiToken',
                'token' => hash('sha256', $user->api_token),
                'abilities' => [ApiToken::ABILITY_USER_ACCESS],
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }

    public function createSystemUser(): void
    {
        User::forceCreate([
            'id' => '0',
            'name' => 'System',
            'email' => 'system@localhost',
            'password' => Hash::make(Str::random(128)),
            'two_factor_secret' => encrypt(Str::random(128)),
        ]);

        DB::table('users')->where('email', 'system@localhost')->update(['id' => 0]);
    }
};
