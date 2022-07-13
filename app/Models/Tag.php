<?php

namespace App\Models;

use App\Audits\Modifiers\VisibilityModifier;
use App\Scopes\OrderNameScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class Tag
 *
 * @package App\Models
 * @property int                    $id
 * @property int                    $user_id
 * @property string                 $name
 * @property int                    $visibility
 * @property Carbon|null            $created_at
 * @property Carbon|null            $updated_at
 * @property string|null            $deleted_at
 * @property-read Collection|Link[] $links
 * @property-read User              $user
 * @method static Builder|Tag byUser(int $user_id = null)
 * @method static Builder|Tag publicOnly()
 * @method static Builder|Tag internalOnly()
 * @method static Builder|Tag privateOnly()
 */
class Tag extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use ScopesForUser;
    use ScopesVisibility;
    use SoftDeletes;

    public $fillable = [
        'user_id',
        'name',
        'visibility',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'visibility' => 'integer',
    ];

    public string $langBase = 'tag';

    /**
     * Add the OrderNameScope to the Tag model
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderNameScope());
    }

    /*
     * ========================================================================
     * AUDIT SETTINGS
     */

    public array $auditModifiers = [
        'visibility' => VisibilityModifier::class,
    ];

    /*
     * ========================================================================
     * RELATIONSHIPS
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function links(): BelongsToMany
    {
        return $this->belongsToMany(Link::class, 'link_tags', 'tag_id', 'link_id');
    }

    /*
     * ========================================================================
     * METHODS
     */

    /**
     * Check if the tag name has changed
     *
     * @param int|string $tagId
     * @param string     $newName
     * @return bool
     */
    public static function nameHasChanged(int|string $tagId, string $newName): bool
    {
        $oldName = self::find($tagId)->name ?? null;
        return $oldName !== $newName;
    }
}
