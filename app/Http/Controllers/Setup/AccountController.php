<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\View\View;

/**
 * Class AccountController
 *
 * @package App\Http\Controllers\Setup
 */
class AccountController extends Controller
{
    use RegistersUsers;

    protected function redirectTo(): string
    {
        return route('setup.complete');
    }

    /**
     * @return Factory|View
     */
    public function index()
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
