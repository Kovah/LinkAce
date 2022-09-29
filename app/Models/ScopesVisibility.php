<?php

namespace App\Models;

use App\Enums\ModelAttribute;
use Illuminate\Database\Eloquent\Builder;

trait ScopesVisibility
{
    public function scopeVisibleForUser(Builder $query, int $userId = null, bool $privateSystemAccess = false): Builder
    {
        if (is_null($userId) && auth()->check()) {
            $userId = auth()->id();
        }

        if ($userId === 0) {
            return $privateSystemAccess ? $query : $query->whereNot('visibility', ModelAttribute::VISIBILITY_PRIVATE);
        }

        // Entity must be either public or internal, or have a private status together with the matching user id
        return $query->where(function (Builder $query) use ($userId) {
            $query->where('visibility', ModelAttribute::VISIBILITY_PUBLIC)
                ->orWhere('visibility', ModelAttribute::VISIBILITY_INTERNAL)
                ->orWhere(function (Builder $query) use ($userId) {
                    $query->where('visibility', ModelAttribute::VISIBILITY_PRIVATE)
                        ->where('user_id', $userId);
                });
        });
    }

    public function scopePrivateOnly(Builder $query): Builder
    {
        return $query->where('visibility', ModelAttribute::VISIBILITY_PRIVATE);
    }

    public function scopeInternalOnly(Builder $query): Builder
    {
        return $query->where('visibility', ModelAttribute::VISIBILITY_INTERNAL);
    }

    public function scopePublicOnly(Builder $query): Builder
    {
        return $query->where('visibility', ModelAttribute::VISIBILITY_PUBLIC);
    }
}
