<?php

namespace App\Models;

use App\Enums\ModelAttribute;
use Illuminate\Database\Eloquent\Builder;

trait ScopesVisibility
{
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
