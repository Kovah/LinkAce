<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tag
 *
 * @package App\Models
 * @property int                                                              $id
 * @property int                                                              $user_id
 * @property string                                                           $name
 * @property int                                                              $is_private
 * @property \Carbon\Carbon|null                                              $created_at
 * @property \Carbon\Carbon|null                                              $updated_at
 * @property string|null                                                      $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Link[] $links
 * @property-read \App\Models\User                                            $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag byUser($user_id)
 */
class Tag extends Model
{
    use SoftDeletes;

    public $table = 'tags';

    public $fillable = [
        'user_id',
        'name',
        'is_private',
    ];

    /*
     | ========================================================================
     | SCOPES
     */

    /**
     * Scope for the user relation
     *
     * @param   \Illuminate\Database\Eloquent\Builder $query
     * @param int                                     $user_id
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function links()
    {
        return $this->belongsToMany('App\Models\Link', 'link_tags', 'tag_id', 'link_id');
    }
}
