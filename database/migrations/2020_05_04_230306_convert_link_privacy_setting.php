<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class ConvertLinkPrivacySetting extends Migration
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
            'key' => 'links_private_default',
            'value' => usersettings('private_default') ?? '0',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
