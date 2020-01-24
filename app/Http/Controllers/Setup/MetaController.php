<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

/**
 * Class WelcomeController
 *
 * @package App\Http\Controllers\Setup
 */
class MetaController extends Controller
{
    public function welcome()
    {
        return view('setup.welcome');
    }

    public function complete()
    {
        $this->markSetupCompleted();

        return view('setup.complete');
    }

    protected function markSetupCompleted()
    {
        $envContent = File::get(base_path('.env'));

        $envContent = str_replace('SETUP_COMPLETED=false', 'SETUP_COMPLETED=true', $envContent);

        File::put(base_path('.env'), $envContent);
    }
}
