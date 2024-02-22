<?php

namespace App\Policies;

use App\Enums\ModelAttribute;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Tag $tag): bool
    {
        return $this->userCanAccessTag($user, $tag);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Tag $tag): bool
    {
        return $this->userCanAccessTag($user, $tag);
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $tag->user->is($user);
    }

    public function restore(User $user, Tag $tag): bool
    {
        return $tag->user->is($user);
    }

    public function forceDelete(User $user, Tag $tag): bool
    {
        return $tag->user->is($user);
    }

    // Link must be either owned by user, or be not private
    protected function userCanAccessTag(User $user, Tag $tag): bool
    {
        if ($tag->user_id === $user->id) {
            return true;
        }
        return $tag->visibility !== ModelAttribute::VISIBILITY_PRIVATE;
    }
}
