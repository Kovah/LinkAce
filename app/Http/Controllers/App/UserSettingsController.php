<?php

namespace App\Http\Controllers\App;

use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\ActivityLog;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecoveryCodesRequest;
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
    public function getUserSettings(): View
    {
        return view('app.settings.user', [
            'pageTitle' => trans('settings.user_settings'),
            'user' => auth()->user(),
            'bookmarklet_code' => bookmarkletUrl(),
        ]);
    }

    /**
     * Return the two factor recovery codes of the current user. The password
     * must be provided for every single request, as the codes are a permanent
     * way to bypass the two factor authentication.
     */
    public function getRecoveryCodes(RecoveryCodesRequest $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->two_factor_secret || !$user->two_factor_recovery_codes) {
            throw ValidationException::withMessages([
                'current_password' => trans('settings.two_factor_not_enabled'),
            ]);
        }

        return response()->json(['codes' => $user->recoveryCodes()]);
    }

    public function saveAccountSettings(Request $request): RedirectResponse
    {
        (new UpdateUserProfileInformation())->update($request->user(), $request->input());

        flash(trans('settings.settings_saved'), 'success');
        return redirect()->back();
    }

    public function saveAppSettings(UserSettings $settings, UserSettingsUpdateRequest $request): RedirectResponse
    {
        // Save all validated user settings or update them
        $newSettings = $request->safe()->except(['share']);
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

    public function changeUserPassword(Request $request): RedirectResponse
    {
        (new UpdateUserPassword())->update($request->user(), $request->input());

        flash(trans('settings.password_updated'), 'success');
        return redirect()->back();
    }
}
