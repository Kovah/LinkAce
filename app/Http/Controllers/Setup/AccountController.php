<?php

namespace App\Http\Controllers\Setup;

use App\Actions\Fortify\CreateNewUser;
use App\Enums\Role;
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

    public function index(): View
    {
        return view('setup.account', [
            'pageTitle' => trans('setup.account_setup'),
        ]);
    }

    protected function register(Request $request): RedirectResponse
    {
        $user = (new CreateNewUser())->create($request->input());
        $user->assignRole(Role::ADMIN);

        Auth::login($user, true);

        return redirect()->route('setup.complete');
    }
}
