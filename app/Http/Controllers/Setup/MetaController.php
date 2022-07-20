<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Settings\SystemSettings;
use Illuminate\Contracts\View\View;

class MetaController extends Controller
{
    public function welcome(): View
    {
        return view('setup.welcome');
    }

    public function complete(SystemSettings $settings): View
    {
        $settings->setup_completed = true;
        $settings->save();

        return view('setup.complete');
    }
}
