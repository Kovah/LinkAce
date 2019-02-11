<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class SystemSettingsController
 *
 * @package App\Http\Controllers\App
 */
class SystemSettingsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSystemSettings()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('actions.settings.system');
    }

    /**
     * @param Request $request
     */
    public function saveSystemSettings(Request $request)
    {
        //
    }

    /**
     * Generate a new API token for the current user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateCronToken(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $new_token = Str::random(32);

        Setting::updateOrCreate(
            [
                'key' => 'cron_token',
                'user_id' => null,
            ],
            ['value' => $new_token]
        );

        return response()->json([
            'new_token' => $new_token,
        ]);
    }
}
