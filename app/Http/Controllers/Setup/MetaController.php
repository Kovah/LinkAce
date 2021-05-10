<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
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
        return view('setup.welcome');
    }

    /**
     * Display a final screen after the setup was successful.
     *
     * @return View
     */
    public function complete(): View
    {
        $this->markSetupCompleted();

        return view('setup.complete');
    }

    /**
     * After the setup is finished, we change the SETUP_COMPLETED variable
     * from false to true to prevent the setup from being run again.
     */
    protected function markSetupCompleted(): void
    {
        $envContent = File::get(base_path('.env'));

        $envContent = str_replace('SETUP_COMPLETED=false', 'SETUP_COMPLETED=true', $envContent);

        File::put(base_path('.env'), $envContent);
    }
}
