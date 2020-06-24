<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\View\View;

class AccountController extends Controller
{
    use RegistersUsers;

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
     * @param array $data
     * @return Validator
     */
    protected function validator(array $data): Validator
    {
        return User::validateRegistration($data);
    }

    /**
     * @param array $data
     * @return User
     */
    protected function create(array $data): User
    {
        return User::createUser($data);
    }
}
