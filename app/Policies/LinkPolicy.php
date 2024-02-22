<?php

namespace App\Policies;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Link $link): bool
    {
        return $this->userCanAccessLink($user, $link);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Link $link): bool
    {
        return $this->userCanAccessLink($user, $link);
    }

    public function delete(User $user, Link $link): bool
    {
        return $link->user->is($user);
    }

    public function restore(User $user, Link $link): bool
    {
        return $link->user->is($user);
    }

    public function forceDelete(User $user, Link $link): bool
    {
        return $link->user->is($user);
    }

    // Link must be either owned by user, or be not private
    protected function userCanAccessLink(User $user, Link $link): bool
    {
        if ($link->user_id === $user->id) {
            return true;
        }
        return $link->visibility !== ModelAttribute::VISIBILITY_PRIVATE;
    }
}
