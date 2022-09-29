<?php

namespace App\Policies\Api;

use App\Enums\ModelAttribute;
use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NoteApiPolicy
{
    use HandlesAuthorization;

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

    // Link must be either owned by user, or be not private
    protected function userCanAccessNote(User $user, Note $note): bool
    {
        if ($note->user_id === $user->id) {
            return true;
        }
        return $note->visibility !== ModelAttribute::VISIBILITY_PRIVATE;
    }
}
