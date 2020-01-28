<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class CronController extends Controller
{
    /**
     * @param Request     $request
     * @param null|string $cron_token
     * @return ResponseFactory|Response
     */
    public function run(Request $request, $cron_token)
    {
        // Verify the cron token
        if (!$cron_token || $cron_token !== systemsettings('cron_token')) {
            abort(403);
        }

        // Run all cron tasks
        Artisan::call('schedule:run');

        return response('Cron successfully executed');
    }
}
