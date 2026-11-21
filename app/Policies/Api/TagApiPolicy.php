<?php

namespace App\Policies\Api;

use App\Enums\ApiToken;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagApiPolicy
{
    use HandlesAuthorization;
    use AuthorizesUserApiActions;

    protected string $readAbility = ApiToken::ABILITY_TAGS_READ;
    protected string $updateAbility = ApiToken::ABILITY_TAGS_UPDATE;
    protected string $deleteAbility = ApiToken::ABILITY_TAGS_DELETE;

    public function viewAny(User $user): bool
    {
        if ($user->isSystemUser()) {
            return $user->tokenCan(ApiToken::ABILITY_TAGS_READ);
        }
        return true;
    }

    public function view(User $user, Tag $tag): bool
    {
        return $this->userCanAccessModel($user, $tag);
    }

    public function create(User $user): bool
    {
        return !$user->isSystemUser();
    }

    public function update(User $user, Tag $tag): bool
    {
        return $this->userCanUpdateModel($user, $tag);
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $this->userCanDeleteModel($user, $tag);
    }

    public function restore(User $user, Tag $tag): bool
    {
        return $this->userCanUpdateModel($user, $tag);
    }

    public function forceDelete(User $user, Tag $tag): bool
    {
        return $this->userCanDeleteModel($user, $tag);
    }
}
