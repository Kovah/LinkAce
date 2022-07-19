<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Laravel\Sanctum\PersonalAccessToken;

class ApiTokenPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PersonalAccessToken $personalAccessToken): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, PersonalAccessToken $personalAccessToken): bool
    {
        return false;
    }

    public function delete(User $user, PersonalAccessToken $personalAccessToken): bool
    {
        return $personalAccessToken->tokenable->is($user);
    }

    public function restore(User $user, PersonalAccessToken $personalAccessToken): bool
    {
        return false;
    }

    public function forceDelete(User $user, PersonalAccessToken $personalAccessToken): bool
    {
        return false;
    }
}
