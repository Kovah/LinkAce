<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

/**
 * Class RequirementsController
 *
 * @package App\Http\Controllers\Setup
 */
class RequirementsController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('setup.requirements');
    }

    public function check()
    {
        //
    }
}
