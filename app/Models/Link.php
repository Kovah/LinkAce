<?php

namespace App\Models;

use App\Audits\Modifiers\BooleanModifier;
use App\Audits\Modifiers\LinkStatusModifier;
use App\Audits\Modifiers\ListRelationModifier;
use App\Audits\Modifiers\TagRelationModifier;
use App\Audits\Modifiers\VisibilityModifier;
use App\Jobs\SaveLinkToWaybackmachine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class Link
 *
 * @package App\Models
 * @property int           $id
 * @property int           $user_id
 * @property string        $url
 * @property string        $title
 * @property string|null   $description
 * @property string|null   $icon
 * @property int           $visibility
 * @property int           $status
 * @property boolean       $check_disabled
 * @property Carbon        $created_at
 * @property Carbon        $updated_at
 * @property string|null   $deleted_at
 * @property MorphMany     $audits
 * @property BelongsToMany $lists
 * @property HasMany       $notes
 * @property BelongsToMany $tags
 * @property BelongsTo     $user
 * @method static Builder|Link  byUser(int $user_id = null)
 * @method static Builder|Link  privateOnly()
 * @method static Builder|Link  internalOnly()
 * @method static Builder|Link  publicOnly()
 */
class Link extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use ScopesForUser;
    use ScopesVisibility;
    use SoftDeletes;

    public $fillable = [
        'user_id',
        'url',
        'title',
        'description',
        'icon',
        'visibility',
        'status',
        'check_disabled',
        'thumbnail',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'visibility' => 'integer',
        'status' => 'integer',
        'check_disabled' => 'boolean',
    ];

    public static array $allowOrderBy = [
        'id',
        'url',
        'title',
        'description',
        'visibility',
        'status',
        'check_disabled',
        'created_at',
        'updated_at',
    ];

    public string $langBase = 'link';

    public const STATUS_OK = 1;
    public const STATUS_MOVED = 2;
    public const STATUS_BROKEN = 3;

    public const DISPLAY_CARDS = 1;
    public const DISPLAY_CARDS_DETAILED = 3;
    public const DISPLAY_LIST_SIMPLE = 2;
    public const DISPLAY_LIST_DETAILED = 0;

    /*
     * ========================================================================
     * AUDIT SETTINGS
     */

    public const AUDIT_RELATION_EVENT = 'relatedModels';
    public const AUDIT_TAGS_NAME = 'revtags';
    public const AUDIT_LISTS_NAME = 'revlists';

    protected array $auditExclude = [
        'icon',
    ];

    public array $auditModifiers = [
        'visibility' => VisibilityModifier::class,
        'check_disabled' => BooleanModifier::class,
        'status' => LinkStatusModifier::class,
        self::AUDIT_TAGS_NAME => TagRelationModifier::class,
        self::AUDIT_LISTS_NAME => ListRelationModifier::class,
    ];

    /*
     * ========================================================================
     * RELATIONSHIPS
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function lists(): BelongsToMany
    {
        return $this->belongsToMany(LinkList::class, 'link_lists', 'link_id', 'list_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'link_tags', 'link_id', 'tag_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'link_id');
    }

    /*
     * ========================================================================
     * METHODS
     */

    /**
     * Get the Markdown formatted description of the link
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
     * Get the URL shortened to max 50 characters and with https:// removed.
     * Other protocols like magnet://, ftp:// and so on will be kept to make
     * those protocols more obvious for the user.
     *
     * @param int $maxLength
     * @return string
     */
    public function shortUrl(int $maxLength = 50): string
    {
        return preg_replace('/http(s)?:\/\//', '', Str::limit(trim($this->url, '/'), $maxLength));
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

    /**
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
