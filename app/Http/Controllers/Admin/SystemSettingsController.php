<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActivityLog;
use App\Helper\UpdateHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SystemSettingsUpdateRequest;
use App\Settings\GuestSettings;
use App\Settings\SystemSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class SystemSettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.system-settings.index', [
            'linkaceVersion' => UpdateHelper::currentVersion(),
        ]);
    }

    public function update(SystemSettingsUpdateRequest $request): RedirectResponse
    {
        $sysSettings = app(SystemSettings::class);

        // Only save settings which belong to this form, never arbitrary keys
        $settings = $request->safe()->only([
            'page_title',
            'logo_text',
            'additional_footer_link_url',
            'additional_footer_link_text',
            'contact_page_enabled',
            'contact_page_title',
            'contact_page_content',
            'custom_header_content',
        ]);

        foreach ($settings as $key => $value) {
            $sysSettings->$key = $value;
        }

        $sysSettings->save();

        flash(trans('settings.settings_saved'));
        return redirect()->route('get-systemsettings');
    }

    public function updateGuest(SystemSettingsUpdateRequest $request): RedirectResponse
    {
        $guestSettings = app(GuestSettings::class);
        $systemSettings = app(SystemSettings::class);

        if ($request->has('guest_access_enabled')) {
            $systemSettings->guest_access_enabled = $request->boolean('guest_access_enabled');
        }

        // Only save settings which belong to this form, never arbitrary keys
        $settings = $request->safe()->only([
            'locale',
            'listitem_count',
            'links_new_tab',
            'darkmode_setting',
        ]);

        foreach ($settings as $key => $value) {
            $guestSettings->$key = $value;
        }

        // Enable / disable sharing services for guests
        $guestSharingSettings = $request->input('guest_share');
        if ($guestSharingSettings) {
            foreach (config('sharing.services') as $service => $details) {
                $guestSettings->{'share_' . $service} = array_key_exists($service, $guestSharingSettings);
            }
        }

        $systemSettings->save();
        $guestSettings->save();

        flash(trans('settings.settings_saved'));
        return redirect()->route('get-systemsettings');
    }

    public function reindexSearch(): RedirectResponse
    {
        $driver = config('linkace.search.driver');

        if ($driver === 'database') {
            flash(trans('settings.search_reindex_database'), 'warning');
            return redirect()->route('get-systemsettings');
        }

        $exitCode = Artisan::call('search:rebuild');

        if ($exitCode === 0) {
            flash(trans('settings.search_reindex_successful'), 'success');
        } else {
            flash(trans('settings.search_reindex_failed'), 'danger');
        }

        return redirect()->route('get-systemsettings');
    }

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
