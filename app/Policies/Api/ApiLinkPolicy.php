<?php

namespace App\Policies\Api;

use App\Enums\ApiToken;
use App\Models\Api\ApiLink;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApiLinkPolicy
{
    use HandlesAuthorization;
    use AuthorizesUserApiActions;

    protected string $readAbility = ApiToken::ABILITY_LINKS_READ;
    protected string $updateAbility = ApiToken::ABILITY_LINKS_UPDATE;
    protected string $deleteAbility = ApiToken::ABILITY_LINKS_DELETE;

    public function viewAny(User $user): bool
    {
        if ($user->isSystemUser()) {
            return $user->tokenCan(ApiToken::ABILITY_LINKS_READ);
        }
        return true;
    }

    public function view(User $user, ApiLink $link): bool
    {
        return $this->userCanAccessModel($user, $link);
    }

    public function create(User $user): bool
    {
        return !$user->isSystemUser();
    }

    public function update(User $user, ApiLink $link): bool
    {
        return $this->userCanUpdateModel($user, $link);
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
}
