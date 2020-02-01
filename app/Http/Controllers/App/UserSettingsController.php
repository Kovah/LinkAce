<?php

namespace App\Http\Controllers\App;

use App\Helper\LinkAce;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserAccountUpdateRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\UserSettingsUpdateRequest;
use App\Models\Setting;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Class UserSettingsController
 *
 * @package App\Http\Controllers\App
 */
class UserSettingsController extends Controller
{
    /**
     * @return Factory|View
     */
    public function getUserSettings()
    {
        $bookmarkletCode = LinkAce::generateBookmarkletCode();

        return view('actions.settings.user', [
            'user' => auth()->user(),
            'bookmarklet_code' => $bookmarkletCode,
        ]);
    }

    /**
     * @param UserAccountUpdateRequest $request
     * @return RedirectResponse
     */
    public function saveAccountSettings(UserAccountUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $user->update($request->only([
            'name',
            'email',
        ]));

        alert(trans('settings.settings_saved'), 'success');
        return redirect()->back();
    }

    /**
     * @param UserSettingsUpdateRequest $request
     * @return RedirectResponse
     */
    public function saveAppSettings(UserSettingsUpdateRequest $request): RedirectResponse
    {
        $userId = auth()->id();

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

        alert(trans('settings.settings_saved'), 'success');
        return redirect()->back();
    }

    /**
     * @param UserPasswordUpdateRequest $request
     * @return RedirectResponse
     */
    public function changeUserPassword(UserPasswordUpdateRequest $request): RedirectResponse
    {
        $currentUser = auth()->user();

        $authorizationSuccessful = Auth::attempt([
            'email' => $currentUser->email,
            'password' => $request->input('old_password'),
        ]);

        if (!$authorizationSuccessful) {
            alert(trans('settings.old_password_invalid'));
            return redirect()->back()->withInput();
        }

        $currentUser->password = Hash::make($request->input('new_password'));
        $currentUser->save();

        alert(trans('settings.password_updated'), 'success');
        return redirect()->back();
    }

    /**
     * Generate a new API token for the current user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateApiToken(Request $request): JsonResponse
    {
        $new_token = Str::random(32);

        $user = auth()->user();
        $user->api_token = $new_token;
        $user->save();

        return response()->json([
            'new_token' => $new_token,
        ]);
    }
}
