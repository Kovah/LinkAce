<?php

namespace App\Http\Controllers\App;

use App\Enums\ActivityLog;
use App\Helper\UpdateHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SystemSettingsUpdateRequest;
use App\Settings\GuestSettings;
use App\Settings\SystemSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
        $sysSettings = app(SystemSettings::class);
        $guestSettings = app(GuestSettings::class);

        $settings = $request->except(['_token', 'guest_share']);

        foreach ($settings as $key => $value) {
            $sysSettings->$key = $value;
        }

        $sysSettings->save();

        // Enable / disable sharing services for guests
        $guestSharingSettings = $request->input('guest_share');
        if ($guestSharingSettings) {
            foreach (config('sharing.services') as $service => $details) {
                $guestSettings->{'share_' . $service} = array_key_exists($service, $guestSharingSettings);
            }
        }

        $guestSettings->save();

        flash(trans('settings.settings_saved'));
        return redirect()->route('get-systemsettings');
    }

    /**
     * Generate a new API token for the current user.
     *
     * @return JsonResponse
     */
    public function generateCronToken(SystemSettings $settings): JsonResponse
    {
        $newToken = Str::random(32);

        $settings->cron_token = $newToken;
        $settings->save();

        activity()->by(auth()->user())->log(ActivityLog::SYSTEM_CRON_TOKEN_REGENERATED);

        return response()->json([
            'new_token' => $newToken,
        ]);
    }
}
