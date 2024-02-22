<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\UserInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function acceptInvitation(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, trans('admin.user_management.invite_link_invalid'));
        }

        $token = $request->input('token');
        $invitation = UserInvitation::where('token', $token)->first();

        if ($invitation === null) {
            abort(401, trans('admin.user_management.invite_token_invalid'));
        }

        if (!$invitation->isValid()) {
            abort(401, trans('admin.user_management.invite_expired'));
        }

        return view('auth.register', [
            'invitation' => $invitation,
        ]);
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $invitation = UserInvitation::where('token', $request->input('token'))->first();

        if ($invitation === null) {
            abort(401, trans('admin.user_management.invite_token_invalid'));
        }

        if (!$invitation->isValid()) {
            abort(401, trans('admin.user_management.invite_expired'));
        }

        $newUser = (new CreateNewUser())->create($request->input());
        Auth::login($newUser, true);

        $invitation->created_user_id = $newUser->id;
        $invitation->save();

        return redirect()->route('dashboard');
    }
}
