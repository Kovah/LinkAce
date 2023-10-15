<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class MetaController extends Controller
{
    /**
     * Display a very simple welcome screen to start the setup process.
     *
     * @return View
     */
    public function welcome(): View
    {
        return view('setup.welcome', [
            'pageTitle' => trans('setup.setup'),
        ]);
    }

    /**
     * Display a final screen after the setup was successful.
     *
     * @return View
     */
    public function complete(): View
    {
        Setting::create(['key' => 'system_setup_completed', 'value' => true]);
        Cache::forget('systemsettings');

        return view('setup.complete', [
            'pageTitle' => trans('setup.complete'),
        ]);
    }
}
