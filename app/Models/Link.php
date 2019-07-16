<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Link
 *
 * @package App\Models
 * @property int                    $id
 * @property int                    $user_id
 * @property int|null               $category_id
 * @property string                 $url
 * @property string                 $title
 * @property string|null            $description
 * @property string|null            $icon
 * @property int                    $is_private
 * @property int                    $status
 * @property \Carbon\Carbon|null    $created_at
 * @property \Carbon\Carbon|null    $updated_at
 * @property string|null            $deleted_at
 * @property-read Category|null     $category
 * @property-read Collection|Note[] $notes
 * @property-read Collection|Tag[]  $tags
 * @property-read User              $user
 * @method static Builder|Link byUser($user_id)
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
        'icon',
        'is_private',
        'status',
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

    /**
     * Scope for the user relation
     *
     * @param Builder $query
     * @param bool    $is_private
     * @return mixed
     */
    public function scopePrivateOnly($query, bool $is_private)
    {
        return $query->where('is_private', $is_private);
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
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'link_tags', 'link_id', 'tag_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class, 'link_id');
    }

    /*
     | ========================================================================
     | METHODS
     */

    /**
     * Display the short URL if it's longer than 60 characters
     *
     * @return string
     */
    public function shortUrl()
    {
        if (mb_strlen($this->url) > 50) {
            return substr($this->url, 0, 50) . '...';
        }

        return $this->url;
    }

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
     * @param string|null $additional_classes
     * @return string
     */
    public function getIcon(?string $additional_classes = null): string
    {
        if ($this->icon === null) {
            return '';
        }

        $icon = $this->icon;
        $title = null;

        // Override the icon by status if applicable
        if ($this->status === 2) {
            $icon = 'fa fa-external-link-alt text-warning';
            $title = trans('link.status.2');
        }

        if ($this->status === 3) {
            $icon = 'fa fa-unlink text-danger';
            $title = trans('link.status.3');
        }

        // Build the correct attributes
        $classes = 'fa-fw ' . $icon . ($additional_classes ? ' ' . $additional_classes : '');
        $title = $title ? ' title="' . $title . '"' : '';

        return '<i class="' . $classes . '" ' . $title . '></i>';
    }

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
