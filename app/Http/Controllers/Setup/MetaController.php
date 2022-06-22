<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Settings\SystemSettings;
use Illuminate\Contracts\View\View;

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
     * @param SystemSettings $settings
     * @return View
     */
    public function complete(SystemSettings $settings): View
    {
        $settings->setup_completed = true;
        $settings->save();

        return view('setup.complete');
    }
}
