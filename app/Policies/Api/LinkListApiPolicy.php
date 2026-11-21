<?php

namespace App\Policies\Api;

use App\Enums\ApiToken;
use App\Models\Api\ApiLinkList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkListApiPolicy
{
    use HandlesAuthorization;
    use AuthorizesUserApiActions;

    protected string $readAbility = ApiToken::ABILITY_LISTS_READ;
    protected string $updateAbility = ApiToken::ABILITY_LISTS_UPDATE;
    protected string $deleteAbility = ApiToken::ABILITY_LISTS_DELETE;

    public function viewAny(User $user): bool
    {
        if ($user->isSystemUser()) {
            return $user->tokenCan(ApiToken::ABILITY_LISTS_READ);
        }
        return true;
    }

    public function view(User $user, ApiLinkList $list): bool
    {
        return $this->userCanAccessModel($user, $list);
    }

    public function create(User $user): bool
    {
        return !$user->isSystemUser();
    }

    public function update(User $user, ApiLinkList $list): bool
    {
        return $this->userCanUpdateModel($user, $list);
    }

    public function delete(User $user, ApiLinkList $list): bool
    {
        return $this->userCanDeleteModel($user, $list);
    }

    public function restore(User $user, ApiLinkList $list): bool
    {
        return $this->userCanUpdateModel($user, $list);
    }

    public function forceDelete(User $user, ApiLinkList $list): bool
    {
        return $this->userCanDeleteModel($user, $list);
    }
}
