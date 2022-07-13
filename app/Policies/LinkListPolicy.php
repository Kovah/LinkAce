<?php

namespace App\Policies;

use App\Enums\ModelAttribute;
use App\Models\LinkList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkListPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, LinkList $list): bool
    {
        return $this->userCanAccessList($user, $list);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, LinkList $list): bool
    {
        return $this->userCanAccessList($user, $list);
    }

    public function delete(User $user, LinkList $list): bool
    {
        return $this->userCanAccessList($user, $list);
    }

    public function restore(User $user, LinkList $list): bool
    {
        return $this->userCanAccessList($user, $list);
    }

    public function forceDelete(User $user, LinkList $list): bool
    {
        return $this->userCanAccessList($user, $list);
    }

    // Link must be either owned by user, or be not private
    protected function userCanAccessList(User $user, LinkList $list): bool
    {
        if ($list->user_id === $user->id) {
            return true;
        }
        return $list->visibility !== ModelAttribute::VISIBILITY_PRIVATE;
    }
}
