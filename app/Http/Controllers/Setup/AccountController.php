<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

/**
 * Class AccountController
 *
 * @package App\Http\Controllers\Setup
 */
class AccountController extends Controller
{
    use RegistersUsers;

    protected function redirectTo()
    {
        return route('setup.complete');
    }

    public function index()
    {
        return view('setup.account');
    }

    protected function validator(array $data): Validator
    {
        return User::validateRegistration($data);
    }

    protected function create(array $data): User
    {
        return User::createUser($data);
    }
}
