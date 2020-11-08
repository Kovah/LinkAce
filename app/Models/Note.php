<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Note
 *
 * @package App\Models
 * @property int         $id
 * @property int         $user_id
 * @property int         $link_id
 * @property string      $note
 * @property int         $is_private
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Link   $link
 * @property-read User   $user
 * @method static Builder|Link byUser($user_id)
 */
class Note extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'notes';

    public $fillable = [
        'user_id',
        'link_id',
        'note',
        'is_private',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'link_id' => 'integer',
        'is_private' => 'boolean',
    ];

    /*
     | ========================================================================
     | SCOPES
     */

    /**
     * Scope for the user relation
     *
     * @param Builder $query
     * @param int     $user_id
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
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * @return BelongsTo
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
