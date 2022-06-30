<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use OwenIt\Auditing\Events\AuditCustom;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()->orderBy('name')->paginate();
        return view('admin.user-management.index', ['users' => $users]);
    }

    public function inviteUser()
    {
        //
    }

    public function acceptInvitation()
    {
        //
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
