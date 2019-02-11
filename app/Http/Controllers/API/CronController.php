<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * Class CronController
 *
 * @package App\Http\Controllers
 */
class CronController extends Controller
{
    /**
     * @param Request     $request
     * @param null|string $cron_token
     * @return void
     */
    public function run(Request $request, $cron_token)
    {
        // Verify the cron token
        if (!$cron_token || $cron_token !== systemsettings('cron_token')) {
            abort(403);
        }

        // Run all cron tasks
        Artisan::call('schedule:run');
    }
}
