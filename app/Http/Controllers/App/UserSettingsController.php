<?php

namespace App\Http\Controllers\App;

use App\Helper\LinkAce;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserAccountUpdateRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\UserSettingsUpdateRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserSettingsController extends Controller
{
    /**
     * Display the user settings forms.
     *
     * @return View
     */
    public function getUserSettings(): View
    {
        $bookmarkletCode = LinkAce::generateBookmarkletCode();

        return view('actions.settings.user', [
            'user' => auth()->user(),
            'bookmarklet_code' => $bookmarkletCode,
        ]);
    }

    /**
     * Handles changes of the user account itself.
     *
     * @param UserAccountUpdateRequest $request
     * @return RedirectResponse
     */
    public function saveAccountSettings(UserAccountUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->only([
            'name',
            'email',
        ]));

        flash(trans('settings.settings_saved'), 'success');

        return redirect()->back();
    }

    /**
     * Handle changes of generall application settings like share services.
     *
     * @param UserSettingsUpdateRequest $request
     * @return RedirectResponse
     */
    public function saveAppSettings(UserSettingsUpdateRequest $request): RedirectResponse
    {
        $userId = $request->user()->id;

        // Save all user settings or update them
        $settings = $request->except(['_token', 'share']);
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate([
                'user_id' => $userId,
                'key' => $key,
            ], [
                'value' => $value,
            ]);
        }

        // Enable / disable sharing services
        $userServices = $request->only(['share']);
        $userServices = $userServices['share'] ?? [];

        foreach (config('sharing.services') as $service => $details) {
            $toggle = array_key_exists($service, $userServices);

            Setting::updateOrCreate([
                'user_id' => $userId,
                'key' => 'share_' . $service,
            ], [
                'value' => $toggle,
            ]);
        }

        flash(trans('settings.settings_saved'), 'success');

        return redirect()->back();
    }

    /**
     * Handles the user password change.
     *
     * @param UserPasswordUpdateRequest $request
     * @return RedirectResponse
     */
    public function changeUserPassword(UserPasswordUpdateRequest $request): RedirectResponse
    {
        $currentUser = $request->user();

        $authorizationSuccessful = Auth::attempt([
            'email' => $currentUser->email,
            'password' => $request->input('old_password'),
        ]);

        if (!$authorizationSuccessful) {
            flash(trans('settings.old_password_invalid'));

            return redirect()->back()->withInput();
        }

        $currentUser->password = Hash::make($request->input('new_password'));
        $currentUser->save();

        flash(trans('settings.password_updated'), 'success');

        return redirect()->back();
    }

    /**
     * Generate a new API token for the current user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateApiToken(Request $request): JsonResponse
    {
        $new_token = Str::random(32);

        $request->user()->api_token = $new_token;
        $request->user()->save();

        return response()->json([
            'new_token' => $new_token,
        ]);
    }
}
