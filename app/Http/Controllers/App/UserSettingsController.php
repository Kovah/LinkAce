<?php

namespace App\Http\Controllers\App;

use App\Helper\LinkAce;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserSettingsUpdateRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserSettingsController
 *
 * @package App\Http\Controllers\App
 */
class UserSettingsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @param UserSettingsUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUserSettings(UserSettingsUpdateRequest $request)
    {
        $user = auth()->user();

        $user->update($request->only([
            'username',
            'email',
        ]));

        // Save all user settings or update them
        $settings = $request->only([
            'timezone',
            'private_default',
            'date_format',
            'time_format',
            'listitem_count',
        ]);

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate([
                'user_id' => $user->id,
                'key' => $key,
            ], [
                'value' => $value,
            ]);
        }

        alert(trans('settings.settings_saved'), 'success');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeUserPassword(Request $request)
    {
        $current_user = auth()->user();
        $data = $request->all();

        // Validate the request by checking if the old password is currect
        $data['old_password'] = Auth::attempt([$current_user->email, $data['old_password']]);

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
}
