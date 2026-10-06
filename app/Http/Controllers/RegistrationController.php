<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\UserInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        // The email is bound to the invitation and must not be chosen freely
        if (!$this->emailMatchesInvitation($request->input('email'), $invitation)) {
            abort(401, trans('admin.user_management.invite_email_mismatch'));
        }

        $newUser = DB::transaction(function () use ($request, $invitation) {
            $user = (new CreateNewUser())->create(
                array_merge($request->input(), ['email' => $invitation->email])
            );

            if (!$invitation->consumeFor($user)) {
                // Another request consumed the invitation first, which also
                // rolls back the user created above
                abort(401, trans('admin.user_management.invite_expired'));
            }

            return $user;
        });

        Auth::login($newUser, true);

        return redirect()->route('dashboard');
    }

    private function emailMatchesInvitation(?string $email, UserInvitation $invitation): bool
    {
        return $email !== null && Str::lower($email) === Str::lower($invitation->email);
    }
}
