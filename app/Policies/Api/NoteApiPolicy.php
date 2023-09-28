<?php

namespace App\Policies\Api;

use App\Enums\ApiToken;
use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NoteApiPolicy
{
    use HandlesAuthorization;
    use AuthorizesUserApiActions;

    protected string $readAbility = ApiToken::ABILITY_NOTES_READ;
    protected string $updateAbility = ApiToken::ABILITY_NOTES_UPDATE;
    protected string $deleteAbility = ApiToken::ABILITY_NOTES_DELETE;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Note $note): bool
    {
        return $this->userCanAccessNote($user, $note);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Note $note): bool
    {
        return $this->userCanAccessNote($user, $note);
    }

    public function delete(User $user, Note $note): bool
    {
        return $note->user->is($user);
    }

    public function restore(User $user, Note $note): bool
    {
        return $note->user->is($user);
    }

    public function forceDelete(User $user, Note $note): bool
    {
        return $note->user->is($user);
    }
}
