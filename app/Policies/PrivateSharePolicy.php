<?php

namespace App\Policies;

use App\Models\PrivateShare;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrivateSharePolicy
{
    use HandlesAuthorization;

    public function viewAny(): bool
    {
        return true;
    }

    public function view(): bool
    {
        return true;
    }

    public function create(): bool
    {
        return true;
    }

    public function update(User $user, PrivateShare $share): bool
    {
        return $share->user_id === $user->id;
    }

    public function delete(User $user, PrivateShare $share): bool
    {
        return $share->user_id === $user->id;
    }
}
