<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FrontController extends Controller
{
    /**
     * @return Factory|RedirectResponse|View
     */
    public function __invoke()
    {
        if (!auth()->check()) {
            if (systemsettings('system_guest_access')) {
                return redirect()->route('guest.links.index');
            }

            return view('welcome');
        }

        return redirect()->route('dashboard');
    }
}
