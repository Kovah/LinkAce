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
        return view('actions.settings.system');
    }

    /**
     * Syve the new system settings to the database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveSystemSettings(Request $request)
    {
        $request->validate([
            'system_guest_access' => 'numeric',
        ]);

        $settings = $request->except(['_token']);

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate([
                'key' => $key,
                'user_id' => null,
            ], [
                'key' => $key,
                'value' => $value,
                'user_id' => null,
            ]);
        }

        alert(trans('settings.settings_saved'));

        return redirect()->back();
    }

    /**
     * Generate a new API token for the current user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateCronToken(Request $request)
    {
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
