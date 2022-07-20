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
 * @property int                    $visibility
 * @property Carbon|null            $created_at
 * @property Carbon|null            $updated_at
 * @property string|null            $deleted_at
 * @property-read Collection|Link[] $links
 * @property-read User              $user
 * @method static Builder|LinkList byUser(int $user_id = null)
 * @method static Builder|LinkList privateOnly()
 * @method static Builder|LinkList internalOnly()
 * @method static Builder|LinkList publicOnly()
 */
class LinkList extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use ScopesForUser;
    use ScopesVisibility;
    use SoftDeletes;

    public $table = 'lists';

    public $fillable = [
        'user_id',
        'name',
        'description',
        'visibility',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'visibility' => 'integer',
    ];

    public static array $allowOrderBy = [
        'id',
        'name',
        'description',
        'visibility',
        'created_at',
        'updated_at',
    ];

    public string $langBase = 'list';

    protected static function boot(): void
    {
        parent::boot();

        // Add the OrderNameScope to the Tag model
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

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    /**
     * @return BelongsToMany
     */
    public function links(): BelongsToMany
    {
        return $this->belongsToMany(Link::class, 'link_lists', 'list_id', 'link_id');
    }

    /*
     * ========================================================================
     * METHODS
     */

    public function getFormattedDescriptionAttribute(): string
    {
        if ($this->description === null) {
            return '';
        }

        if (usersettings('markdown_for_text') === false) {
            return htmlentities($this->description);
        }

        return Str::markdown($this->description, ['html_input' => 'escape']);
    }
}
