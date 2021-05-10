<?php

namespace App\Http\Controllers\Setup;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    protected function redirectTo(): string
    {
        return route('setup.complete');
    }

    /**
     * Display the registration form for the first user account.
     *
     * @return View
     */
    public function index(): View
    {
        return view('setup.account');
    }

    /**
     * Validate and create the new user, then login him, and redirect him to the dashboard
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    protected function register(Request $request): RedirectResponse
    {
        $user = (new CreateNewUser())->create($request->input());

        Auth::login($user, true);

        return redirect()->route('setup.complete');
    }
}
