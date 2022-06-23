<?php

use App\Enums\ModelAttribute;
use App\Models\LinkList;
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
}
