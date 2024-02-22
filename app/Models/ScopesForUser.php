<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

trait ScopesForUser
{
    /**
     * Scope for the user relation. If a user is specified, it will be used for
     * the scope. Otherwise, the currently authenticated user will be used.
     *
     * @param Builder  $query
     * @param int|null $userId
     * @return Builder
     */
    public function scopeByUser(Builder $query, int $userId = null): Builder
    {
        if (is_null($userId) && auth()->check()) {
            $userId = auth()->id();
        }
        return $query->where('user_id', $userId);
    }
}
