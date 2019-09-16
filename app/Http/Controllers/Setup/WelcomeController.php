<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

/**
 * Class WelcomeController
 *
 * @package App\Http\Controllers\Setup
 */
class WelcomeController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('setup.welcome');
    }
}
