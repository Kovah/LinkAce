<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FrontController extends Controller
{
    /**
     * The front controller checks if the user is authenticated and the guest
     * access is enabled, and redirects the request accordingly.
     *
     * @return RedirectResponse|View
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
