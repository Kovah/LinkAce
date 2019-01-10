<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Link
 *
 * @package App\Models
 * @property int                                                              $id
 * @property int                                                              $user_id
 * @property int|null                                                         $category_id
 * @property string                                                           $url
 * @property string                                                           $title
 * @property string|null                                                      $description
 * @property int                                                              $is_private
 * @property \Carbon\Carbon|null                                              $created_at
 * @property \Carbon\Carbon|null                                              $updated_at
 * @property string|null                                                      $deleted_at
 * @property-read \App\Models\Category|null                                   $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Note[] $notes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[]  $tags
 * @property-read \App\Models\User                                            $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link byUser($user_id)
 */
class Link extends Model
{
    use SoftDeletes;

    public $table = 'links';

    public $fillable = [
        'user_id',
        'category_id',
        'url',
        'title',
        'description',
        'is_private',
    ];

    /*
     | ========================================================================
     | SCOPES
     */

    /**
     * Scope for the user relation
     *
     * @param    \Illuminate\Database\Eloquent\Builder $query
     * @param int                                      $user_id
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
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'link_tags', 'link_id', 'tag_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany('App\Models\Note', 'link_id');
    }

    /*
     | ========================================================================
     | METHODS
     */

    /**
     * @return null|string
     */
    public function tagsForInput()
    {
        $tags = $this->tags;

        if ($tags->isEmpty()) {
            return null;
        }

        return $tags->implode('name', ',');
    }

    /**
     * Output a relative time inside a span with real time information
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
