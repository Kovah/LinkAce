<?php

namespace App\Http\Controllers\App;

use App\Helper\LinkAce;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserAccountUpdateRequest;
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
        $bookmarklet_code = LinkAce::generateBookmarkletCode();

        return view('actions.settings.user', [
            'user' => auth()->user(),
            'bookmarklet_code' => $bookmarklet_code,
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
        $user_id = auth()->id();

        // Save all user settings or update them
        $settings = $request->except(['_token', 'share']);
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate([
                'user_id' => $user_id,
                'key' => $key,
            ], [
                'value' => $value,
            ]);
        }

        // Enable / disable sharing services
        $user_services = $request->only(['share']);
        $user_services = $user_services['share'] ?? [];

        foreach (config('sharing.services') as $service => $details) {
            $toggle = array_key_exists($service, $user_services);

            Setting::updateOrCreate([
                'user_id' => $user_id,
                'key' => 'share_' . $service,
            ], [
                'value' => $toggle,
            ]);
        }

        alert(trans('settings.settings_saved'), 'success');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function changeUserPassword(Request $request): RedirectResponse
    {
        $current_user = auth()->user();
        $data = $request->all();

        // Validate the request by checking if the old password is currect
        $data['old_password'] = Auth::attempt([
            'email' => $current_user->email,
            'password' => $data['old_password'],
        ]);

        $validator = Validator::make($data, [
            'old_password' => 'accepted',
            'new_password' => 'required|confirmed',
        ], [
            'accepted' => trans('settings.old_password_invalid'),
        ]);

        if ($validator->failed()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Save the new password
        $current_user->password = Hash::make($data['new_password']);
        $current_user->save();

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
