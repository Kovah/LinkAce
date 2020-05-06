<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class EnableInternetArchiveBackupSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::create([
            'user_id' => 1,
            'key' => 'archive_backups_enabled',
            'value' => '1',
        ]);

        Setting::create([
            'user_id' => 1,
            'key' => 'archive_private_backups_enabled',
            'value' => '0',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('key', 'archive_backups_enabled')->delete();
        Setting::where('key', 'archive_private_backups_enabled')->delete();
    }
}
