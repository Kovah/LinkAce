<?php

namespace App\Http\Controllers\App;

use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Helper\LinkAce;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserSettingsUpdateRequest;
use App\Models\Setting;
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
        $bookmarkletCode = LinkAce::generateBookmarkletCode();

        return view('app.settings.user', [
            'user' => auth()->user(),
            'bookmarklet_code' => $bookmarkletCode,
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
