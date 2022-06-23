<?php

use App\Enums\ModelAttribute;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateUserData extends Migration
{
    public function up()
    {
        $this->migrateLinkVisibility();
    }

    protected function migrateLinkVisibility(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->integer('visibility')->default(ModelAttribute::VISIBILITY_PRIVATE)->after('is_private');
        });

        $guestModeEnabled = systemsettings('guest_access_enabled');
        Link::query()->chunk(500, function ($links) use ($guestModeEnabled) {
            foreach ($links as $link) {
                $link->visibility = match ((bool)$link->is_private) {
                    true => ModelAttribute::VISIBILITY_PRIVATE,
                    false => $guestModeEnabled
                        ? ModelAttribute::VISIBILITY_PUBLIC : ModelAttribute::VISIBILITY_INTERNAL,
                };
                $link->saveQuietly();
            }
        });

        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn(['is_private']);
        });
    }
}
