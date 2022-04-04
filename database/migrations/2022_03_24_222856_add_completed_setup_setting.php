<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

class AddCompletedSetupSetting extends Migration
{
    public function up()
    {
        if (config('app.setup_completed')) {
            Setting::updateOrCreate(['key' => 'system_setup_completed', 'value' => true]);
            Cache::forget('systemsettings');
        }
    }
}
