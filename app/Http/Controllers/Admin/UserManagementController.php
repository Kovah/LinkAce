<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InviteUserRequest;
use App\Models\User;
use App\Models\UserInvitation;
use App\Notifications\UserInviteNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use OwenIt\Auditing\Events\AuditCustom;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()->orderBy('name')->paginate(pageName: 'user_page');
        $invitations = UserInvitation::query()->latest()->paginate(pageName: 'invite_page');
        return view('admin.user-management.index', [
            'users' => $users,
            'invitations' => $invitations,
        ]);
    }

    public function inviteUser(InviteUserRequest $request): RedirectResponse
    {
        $invitation = UserInvitation::create([
            'token' => Str::random(32),
            'email' => $request->input('email'),
            'inviter_id' => $request->user()->id,
            'valid_until' => now()->addDays(3),
        ]);

        $invitation->notify(new UserInviteNotification());

        flash()->warning(trans('admin.user_management.invite_successful'));
        return redirect()->back();
    }

    public function acceptInvitation()
    {
        // @TODO
        // check if request is valid, check if invitation with token was found
        // present view with registration form and pre-filled email
        // handle user registration
    }

    public function deleteInvitation(UserInvitation $invitation): RedirectResponse
    {
        $invitation->delete();

        flash()->warning(trans('admin.user_management.invite_delete_successful', ['email' => $invitation->email]));
        return redirect()->back();
    }

    public function blockUser(User $user): RedirectResponse
    {
        $this->checkIfActionAllowed($user);

        $user->blocked_at = now();
        $user->save();

        $this->addBlockingAuditEvent($user, User::AUDIT_BLOCK_EVENT, null, $user->blocked_at);

        flash()->warning(trans('user.block_successful', ['username' => $user->name]));
        return redirect()->back();
    }

    public function unblockUser(User $user): RedirectResponse
    {
        $this->checkIfActionAllowed($user);

        $blockedAt = $user->blocked_at;
        $user->blocked_at = null;
        $user->save();

        $this->addBlockingAuditEvent($user, User::AUDIT_UNBLOCK_EVENT, $blockedAt, null);

        flash()->warning(trans('user.unblock_successful', ['username' => $user->name]));
        return redirect()->back();
    }

    public function deleteUser(User $user): RedirectResponse
    {
        $this->checkIfActionAllowed($user);

        $user->delete();

        flash()->warning(trans('user.delete_successful', ['username' => $user->name]));
        return redirect()->back();
    }

    public function restoreUser(User $user): RedirectResponse
    {
        $this->checkIfActionAllowed($user);

        $user->restore();

        flash()->warning(trans('user.restore_successful', ['username' => $user->name]));
        return redirect()->back();
    }

    protected function checkIfActionAllowed(User $user): void
    {
        if ($user->id === auth()->id()) {
            abort(403, trans('user.action_not_allowed_on_user'));
        }
    }

    protected function addBlockingAuditEvent(User $user, string $event, $oldValue, $newValue): void
    {
        $user->auditEvent = $event;
        $user->isCustomEvent = true;
        $user->auditCustomOld = [
            'blocked_at' => $oldValue,
        ];
        $user->auditCustomNew = [
            'blocked_at' => $newValue,
        ];

        Event::dispatch(AuditCustom::class, [$user]);
    }
}
