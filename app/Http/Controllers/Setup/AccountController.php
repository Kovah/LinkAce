<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

/**
 * Class AccountController
 *
 * @package App\Http\Controllers\Setup
 */
class AccountController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('setup.account');
    }
}
