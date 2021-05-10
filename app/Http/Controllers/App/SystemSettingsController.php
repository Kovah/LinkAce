<?php

namespace App\Http\Controllers\App;

use App\Helper\UpdateHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SystemSettingsUpdateRequest;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SystemSettingsController extends Controller
{
    /**
     * Display the system settings forms.
     *
     * @return View
     */
    public function getSystemSettings(): View
    {
        return view('app.settings.system', [
            'linkaceVersion' => UpdateHelper::currentVersion(),
        ]);
    }

    /**
     * Save the updated system settings to the database.
     *
     * @param SystemSettingsUpdateRequest $request
     * @return RedirectResponse
     */
    public function saveSystemSettings(SystemSettingsUpdateRequest $request): RedirectResponse
    {
        $settings = $request->except(['_token', 'guest_share']);

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

        // Enable / disable sharing services
        $guestSharingSettings = $request->input('guest_share');

        if ($guestSharingSettings) {
            foreach (config('sharing.services') as $service => $details) {
                $toggle = array_key_exists($service, $guestSharingSettings);

                Setting::updateOrCreate([
                    'user_id' => null,
                    'key' => 'guest_share_' . $service,
                ], [
                    'key' => 'guest_share_' . $service,
                    'value' => $toggle,
                    'user_id' => null,
                ]);
            }
        }

        Cache::forget('systemsettings');

        flash(trans('settings.settings_saved'));
        return redirect()->route('get-systemsettings');
    }

    /**
     * Generate a new API token for the current user.
     *
     * @return JsonResponse
     */
    public function generateCronToken(): JsonResponse
    {
        $newToken = Str::random(32);

        Setting::updateOrCreate(
            [
                'key' => 'cron_token',
                'user_id' => null,
            ],
            ['value' => $newToken]
        );

        Cache::forget('systemsettings');

        return response()->json([
            'new_token' => $newToken,
        ]);
    }
}
