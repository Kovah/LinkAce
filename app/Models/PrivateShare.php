<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

/**
 * Class PrivateShare
 *
 * @package App\Models
 * @property int                    $id
 * @property string                 $ident
 * @property int                    $entity_id
 * @property string                 $entity_type
 * @property Carbon|null            $expires_at
 * @property Carbon|null            $created_at
 * @property Carbon|null            $updated_at
 * @property-read Link|LinkList|Tag $entity
 */
class PrivateShare extends Model
{
    use HasFactory;

    public $fillable = [
        'ident',
        'user_id',
        'entity_id',
        'entity_type',
        'expires_at',
        // @TODO add view count restriction?
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected $with = [
        'entity',
    ];

    public function getRouteKeyName(): string
    {
        return 'ident';
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return URL::signedRoute('guest.private-share', ['private_share' => $this]);
    }
}
