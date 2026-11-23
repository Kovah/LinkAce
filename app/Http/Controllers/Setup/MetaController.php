<?php

namespace App\Http\Controllers\Setup;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Settings\SystemSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MetaController extends Controller
{
    public function welcome(): View
    {
        return view('setup.welcome', [
            'pageTitle' => trans('setup.setup'),
        ]);
    }

    public function complete(SystemSettings $settings): View|RedirectResponse
    {
        // Do not complete the setup without an admin account, as nobody could log in afterwards
        if (!User::role(Role::ADMIN)->exists()) {
            return redirect()->route('setup.account');
        }

        $settings->setup_completed = true;
        $settings->save();

        return view('setup.complete', [
            'pageTitle' => trans('setup.complete'),
        ]);
    }
}
