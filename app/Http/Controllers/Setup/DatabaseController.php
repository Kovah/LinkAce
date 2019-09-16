<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

/**
 * Class DatabaseController
 *
 * @package App\Http\Controllers\Setup
 */
class DatabaseController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('setup.database');
    }

    /**
     * @return Factory|View
     */
    public function configure()
    {
        return view('setup.database');
    }
}
