<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Link
 *
 * @package App\Models
 * @property int                    $id
 * @property int                    $user_id
 * @property string                 $url
 * @property string                 $title
 * @property string|null            $description
 * @property string|null            $icon
 * @property boolean                $is_private
 * @property int                    $status
 * @property boolean                $check_disabled
 * @property Carbon|null            $created_at
 * @property Carbon|null            $updated_at
 * @property string|null            $deleted_at
 * @property-read Collection|Tag[]  $lists
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
        'url',
        'title',
        'description',
        'icon',
        'is_private',
        'status',
        'check_disabled',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'is_private' => 'boolean',
        'status' => 'integer',
        'check_disabled' => 'boolean',
    ];

    public const STATUS_OK = 1;
    public const STATUS_MOVED = 2;
    public const STATUS_BROKEN = 3;

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
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsToMany
     */
    public function lists()
    {
        return $this->belongsToMany(LinkList::class, 'link_lists', 'link_id', 'list_id');
    }

    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'link_tags', 'link_id', 'tag_id');
    }

    /**
     * @return HasMany
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
     * @return null|string
     */
    public function listsForInput()
    {
        $lists = $this->lists;

        if ($lists->isEmpty()) {
            return null;
        }

        return $lists->implode('name', ',');
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

    /**
     * @param string|int $linkId
     * @param string     $newUrl
     * @return bool
     */
    public static function urlHasChanged($linkId, string $newUrl): bool
    {
        $oldUrl = self::find($linkId)->url ?? null;
        return $oldUrl !== $newUrl;
    }
}
