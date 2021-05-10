<?php

namespace App\Models;

use App\Jobs\SaveLinkToWaybackmachine;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Venturecraft\Revisionable\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Link
 *
 * @package App\Models
 * @property int                   $id
 * @property int                   $user_id
 * @property string                $url
 * @property string                $title
 * @property string|null           $description
 * @property string|null           $icon
 * @property boolean               $is_private
 * @property int                   $status
 * @property boolean               $check_disabled
 * @property Carbon|null           $created_at
 * @property Carbon|null           $updated_at
 * @property string|null           $deleted_at
 * @property Collection|Tag[]      $lists
 * @property Collection|Note[]     $notes
 * @property Collection|Revision[] $revisionHistory
 * @property Collection|Tag[]      $tags
 * @property User                  $user
 * @method static Builder|Link  byUser($user_id)
 * @method static Builder|Link  privateOnly()
 * @method static Builder|Link  publicOnly()
 * @method static MorphMany     revisionHistory()
 */
class Link extends Model
{
    use SoftDeletes;
    use RevisionableTrait;
    use HasFactory;

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
        'thumbnail',
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

    public const DISPLAY_CARDS = 1;
    public const DISPLAY_LIST_SIMPLE = 2;
    public const DISPLAY_LIST_DETAILED = 0;

    // Revisions settings
    protected $revisionCleanup = true;
    protected $historyLimit = 50;
    protected $dontKeepRevisionOf = ['icon'];

    public const REV_TAGS_NAME = 'revtags';
    public const REV_LISTS_NAME = 'revlists';


    /*
     | ========================================================================
     | SCOPES
     */

    /**
     * Scope for the user relation
     *
     * @param Builder $query
     * @param int     $userId
     * @return Builder
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for the user relation
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePrivateOnly(Builder $query): Builder
    {
        return $query->where('is_private', true);
    }

    /**
     * Scope for the user relation
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublicOnly(Builder $query): Builder
    {
        return $query->where('is_private', false);
    }

    /*
     | ========================================================================
     | RELATIONSHIPS
     */

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsToMany
     */
    public function lists(): BelongsToMany
    {
        return $this->belongsToMany(LinkList::class, 'link_lists', 'link_id', 'list_id');
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'link_tags', 'link_id', 'tag_id');
    }

    /**
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'link_id');
    }

    /*
     | ========================================================================
     | METHODS
     */

    /**
     * Get the formatted description of the link
     *
     * @return string
     */
    public function getFormattedDescriptionAttribute(): string
    {
        if ($this->description === null) {
            return '';
        }

        if (usersettings('markdown_for_text') !== '1') {
            return htmlentities($this->description);
        }

        return Str::markdown($this->description, ['html_input' => 'escape']);
    }

    /**
     * Get the URL shortened to max 50 characters
     *
     * @return string
     */
    public function shortUrl(int $maxLength = 50): string
    {
        return Str::limit(trim($this->url, '/'), $maxLength);
    }

    /**
     * Get the title shortened to max 50 characters
     *
     * @param int $maxLength
     * @return string
     */
    public function shortTitle(int $maxLength = 50): string
    {
        return Str::limit($this->title, $maxLength);
    }

    /**
     * Get the domain of the URL
     *
     * @return string
     */
    public function domainOfURL(): string
    {
        $urlDetails = parse_url($this->url);
        return $urlDetails['host'] ?? $this->shortUrl(20);
    }

    public function tagsForInput(): ?string
    {
        $tags = $this->tags;

        if ($tags->isEmpty()) {
            return null;
        }

        return $tags->implode('name', ',');
    }

    public function listsForInput(): ?string
    {
        $lists = $this->lists;

        if ($lists->isEmpty()) {
            return null;
        }

        return $lists->implode('name', ',');
    }

    public function getIcon(string $additionalClasses = ''): string
    {
        if ($this->icon === null) {
            return '';
        }

        $icon = $this->icon;
        $title = null;

        // Override the icon by status if applicable
        if ($this->status === self::STATUS_MOVED) {
            $icon = 'external-link';
            $additionalClasses .= ' text-warning';
            $title = trans('link.stati.2');
        }

        if ($this->status === self::STATUS_BROKEN) {
            $icon = 'unlink';
            $additionalClasses .= ' text-danger';
            $title = trans('link.stati.3');
        }

        if (!view()->exists('components.icon.' . $icon)) {
            return "<!-- Icon icon.$icon could not be found! -->";
        }

        return view('models.links.partials.link-icon', [
            'icon' => 'icon.' . $icon,
            'class' => $additionalClasses . ' fw',
            'title' => $title,
        ]);
    }

    /**
     * Output a relative time inside a span with real time information
     *
     * @return string
     */
    public function addedAt(): string
    {
        $output = '<time-ago class="cursor-help"';
        $output .= ' datetime="' . $this->created_at->toIso8601String() . '"';
        $output .= ' title="' . formatDateTime($this->created_at) . '">';
        $output .= formatDateTime($this->created_at, true);
        $output .= '</time-ago>';

        return $output;
    }

    /*
     * Dispatch the SaveLinkToWaybackmachine job, if Internet Archive backups
     * are enabled.
     * If the link is private, private Internet Archive backups must be enabled
     * too.
     */
    public function initiateInternetArchiveBackup(): void
    {
        if (usersettings('archive_backups_enabled') === '0') {
            return;
        }

        if ($this->is_private && usersettings('archive_private_backups_enabled') === '0') {
            return;
        }

        SaveLinkToWaybackmachine::dispatchAfterResponse($this);
    }

    /**
     * Create a base uri of the link url, consisting of a possible auth, the
     * hostname, a port if present, and the path. The scheme, fragments and
     * query parameters are dumped, as well as trailing slashes.
     * Then return all links that match this URI.
     *
     * If the host is not present, the URL might be broken, so do not search
     * for any duplicates.
     *
     * @return Collection
     */
    public function searchDuplicateUrls(): Collection
    {
        $parsed = parse_url($this->url);

        if (!isset($parsed['host'])) {
            return new Collection();
        }

        $auth = $parsed['user'] ?? '';
        $auth .= isset($parsed['pass']) ? ':' . $parsed['pass'] : '';

        $uri = $auth ? $auth . '@' : '';
        $uri .= $parsed['host'] ?? '';
        $uri .= isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $uri .= $parsed['path'] ?? '';

        return self::where('id', '<>', $this->id)
            ->where('url', 'like', '%' . trim($uri, '/') . '%')
            ->get();
    }
}
