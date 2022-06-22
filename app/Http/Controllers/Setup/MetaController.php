<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Settings\SettingsAudit;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class MetaController extends Controller
{
    /**
     * Display a very simple welcome screen to start the setup process.
     *
     * @return View
     */
    public function welcome(): View
    {
        return view('setup.welcome');
    }

    /**
     * Display a final screen after the setup was successful.
     *
     * @return View
     */
    public function complete(): View
    {
        SettingsAudit::create(['key' => 'system_setup_completed', 'value' => true]);
        Cache::forget('systemsettings');

        return view('setup.complete');
    }
}
