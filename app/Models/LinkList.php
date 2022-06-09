<?php

namespace App\Models;

use App\Audits\Modifiers\BooleanModifier;
use App\Scopes\OrderNameScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class LinkList
 *
 * @package  App\Models
 * @property int                    $id
 * @property int                    $user_id
 * @property string                 $name
 * @property ?string                $description
 * @property int                    $is_private
 * @property Carbon|null            $created_at
 * @property Carbon|null            $updated_at
 * @property string|null            $deleted_at
 * @property-read Collection|Link[] $links
 * @property-read User              $user
 * @method static Builder|Tag byUser($user_id)
 * @method static Builder|Tag privateOnly()
 * @method static Builder|Tag publicOnly()
 */
class LinkList extends Model implements Auditable
{
    use AuditableTrait;
    use SoftDeletes;
    use HasFactory;

    public $table = 'lists';

    public $fillable = [
        'user_id',
        'name',
        'description',
        'is_private',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'is_private' => 'boolean',
    ];

    // Audit settings
    public array $auditModifiers = [
        'is_private' => BooleanModifier::class,
    ];

    /**
     * Add the OrderNameScope to the Tag model
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new OrderNameScope());
    }

    /*
     | ========================================================================
     | SCOPES
     */

    /**
     * Scope for the user relation
     *
     * @param Builder $query
     * @param int     $user_id
     * @return Builder
     */
    public function scopeByUser(Builder $query, int $user_id): Builder
    {
        return $query->where('user_id', $user_id);
    }

    /**
     * Scope for selecting private lists only
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePrivateOnly(Builder $query): Builder
    {
        return $query->where('is_private', true);
    }

    /**
     * Scope for selecting public lists only
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
    public function links(): BelongsToMany
    {
        return $this->belongsToMany(Link::class, 'link_lists', 'list_id', 'link_id');
    }

    /*
     | ========================================================================
     | METHODS
     */

    /**
     * Get the formatted description of the list
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
     * Get a collection of all lists for the current user, ordered by name
     *
     * @return Builder[]|Collection
     */
    public static function getAllForCurrentUser(): Collection|array
    {
        return self::byUser(auth()->id())
            ->oldest('name')
            ->get();
    }

    /**
     * Check if the list name has changed
     *
     * @param int|string $listId
     * @param string     $newName
     * @return bool
     */
    public static function nameHasChanged(int|string $listId, string $newName): bool
    {
        $oldName = self::find($listId)->name ?? null;
        return $oldName !== $newName;
    }
}
