<?php

namespace App\Repositories;

use App\Models\PrivateShare;
use Illuminate\Support\Str;

class PrivateShareRepository
{
    public static function create(array $data): PrivateShare
    {
        return PrivateShare::create([
            'ident' => Str::ulid(),
            'user_id' => auth()->id(),
            'entity_id' => $data['entity_id'],
            'entity_type' => $data['entity_type'],
            'expires_at' => $data['expires_at'],
        ]);
    }

    public static function update(PrivateShare $share, array $data): PrivateShare
    {
        $share->update([
            'expires_at' => $data['expires_at'],
        ]);

        return $share;
    }

    public static function delete(PrivateShare $share): bool
    {
        return $share->delete();
    }
}
