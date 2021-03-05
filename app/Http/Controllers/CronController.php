<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class CronController extends Controller
{
    /**
     * This endpoint allows the execution of the cron if a system-controlled
     * cron is not available.
     *
     * @param Request $request
     * @param string  $cronToken
     * @return ResponseFactory|Response
     */
    public function __invoke(Request $request, string $cronToken)
    {
        // Verify the cron token
        if ($cronToken !== systemsettings('cron_token')) {
            return response(trans('settings.cron_token_auth_failure'), 403);
        }

        Artisan::call('schedule:run');

        return response(trans('settings.cron_execute_successful'));
    }
}
