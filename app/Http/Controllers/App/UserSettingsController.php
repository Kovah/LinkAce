<?php

namespace App\Http\Controllers\App;

use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\ActivityLog;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserSettingsUpdateRequest;
use App\Settings\UserSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserSettingsController extends Controller
{
    /**
     * Display the user settings forms.
     *
     * @return View
     */
    public function getUserSettings(): View
    {
        return view('app.settings.user', [
            'user' => auth()->user(),
            'bookmarklet_code' => bookmarkletUrl(),
        ]);
    }

    /**
     * Handles changes of the user account itself.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function saveAccountSettings(Request $request): RedirectResponse
    {
        (new UpdateUserProfileInformation())->update($request->user(), $request->input());

        flash(trans('settings.settings_saved'), 'success');
        return redirect()->back();
    }

    /**
     * Handle changes of general application settings like share services.
     *
     * @param UserSettingsUpdateRequest $request
     * @return RedirectResponse
     */
    public function saveAppSettings(UserSettings $settings, UserSettingsUpdateRequest $request): RedirectResponse
    {
        // Save all user settings or update them
        $newSettings = $request->except(['_token', 'share']);
        foreach ($newSettings as $key => $value) {
            $settings->$key = $value;
        }

        // Enable / disable sharing services
        $userServices = $request->only(['share']);
        $userServices = $userServices['share'] ?? [];

        foreach (config('sharing.services') as $service => $details) {
            $settings->{'share_' . $service} = array_key_exists($service, $userServices);
        }

        $settings->save();

        flash(trans('settings.settings_saved'), 'success');
        return redirect()->back();
    }

    /**
     * Handles the user password change.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function changeUserPassword(Request $request): RedirectResponse
    {
        (new UpdateUserPassword())->update($request->user(), $request->input());

        flash(trans('settings.password_updated'), 'success');
        return redirect()->back();
    }
}
