<?php

namespace App\Policies\Api;

use App\Enums\ApiToken;
use App\Enums\ModelAttribute;
use App\Models\Api\ApiLink;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApiLinkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if ($user->isSystemUser()) {
            return $user->tokenCan(ApiToken::ABILITY_LINKS_READ);
        }
        return true;
    }

    public function view(User $user, ApiLink $link): bool
    {
        return $this->userCanAccessLink($user, $link);
    }

    public function create(User $user): bool
    {
        return !$user->isSystemUser();
    }

    public function update(User $user, ApiLink $link): bool
    {
        return $this->userCanUpdateLink($user, $link);
    }

    public function delete(User $user, ApiLink $link): bool
    {
        return $link->user->is($user);
    }

    public function restore(User $user, ApiLink $link): bool
    {
        return $link->user->is($user);
    }

    public function forceDelete(User $user, ApiLink $link): bool
    {
        return $link->user->is($user);
    }

    // Link must be either owned by user, or be not private
    protected function userCanAccessLink(User $user, ApiLink $link): bool
    {
        if ($link->user_id === $user->id) {
            return true;
        }
        if ($user->isSystemUser()) {
            if ($link->visibility === ModelAttribute::VISIBILITY_PRIVATE) {
                return $user->tokenCan(ApiToken::ABILITY_LINKS_READ) && $user->tokenCan(ApiToken::ABILITY_SYSTEM_ACCESS_PRIVATE);
            }
            return $user->tokenCan(ApiToken::ABILITY_LINKS_READ);
        }
        return $link->visibility !== ModelAttribute::VISIBILITY_PRIVATE;
    }

    protected function userCanUpdateLink(User $user, ApiLink $link): bool
    {
        if ($link->user_id === $user->id) {
            return true;
        }
        if ($user->isSystemUser()) {
            if ($link->visibility === ModelAttribute::VISIBILITY_PRIVATE) {
                return $user->tokenCan(ApiToken::ABILITY_LINKS_UPDATE) && $user->tokenCan(ApiToken::ABILITY_SYSTEM_ACCESS_PRIVATE);
            }
            return $user->tokenCan(ApiToken::ABILITY_LINKS_UPDATE);
        }
        return $link->visibility !== ModelAttribute::VISIBILITY_PRIVATE;
    }
}
