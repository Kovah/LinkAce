<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;

    public $table = 'notes';

    public $fillable = [
        'user_id',
        'note',
        'is_private',
    ];

    /*
     | ========================================================================
     | SCOPES
     */

    /**
     * Scope for the user relation
     *
     * @param     $query
     * @param int $user_id
     * @return mixed
     */
    public function scopeByUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /*
     | ========================================================================
     | RELATIONSHIPS
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function link()
    {
        return $this->belongsTo('App\Models\Link', 'link_id');
    }
}
