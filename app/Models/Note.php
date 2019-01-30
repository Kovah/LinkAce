<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Note
 *
 * @package App\Models
 * @property int                 $id
 * @property int                 $user_id
 * @property int                 $link_id
 * @property string              $note
 * @property int                 $is_private
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null         $deleted_at
 * @property-read Link           $link
 * @property-read User           $user
 * @method static Builder|Link byUser($user_id)
 */
class Note extends RememberedModel
{
    use SoftDeletes;

    public $table = 'notes';

    public $fillable = [
        'user_id',
        'link_id',
        'note',
        'is_private',
    ];

    public $rememberCacheTag = 'note_queries';

    /*
     | ========================================================================
     | SCOPES
     */

    /**
     * Scope for the user relation
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param int                                    $user_id
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

    /*
     | ========================================================================
     | METHODS
     */

    /**
     * Output a relative time inside a span with real time information
     *
     * @return string
     */
    public function addedAt()
    {
        $output = '<time-ago class="cursor-help"';
        $output .= ' datetime="' . $this->created_at->toIso8601String() . '"';
        $output .= ' title="' . formatDateTime($this->created_at) . '">';
        $output .= formatDateTime($this->created_at, true);
        $output .= '</time-ago>';

        return $output;
    }
}
