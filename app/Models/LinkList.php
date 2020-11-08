<?php

namespace App\Models;

use App\Scopes\OrderNameScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @method static Builder|Tag isPrivate(bool $private)
 */
class LinkList extends Model
{
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

    /**
     * Add the OrderNameScope to the Tag model
     */
    protected static function boot()
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
     * @return mixed
     */
    public function scopeByUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /**
     * Scope for selecting private or non-private lists
     *
     * @param Builder $query
     * @param bool    $is_private
     * @return mixed
     */
    public function scopeIsPrivate($query, bool $is_private)
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
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * @return BelongsToMany
     */
    public function links()
    {
        return $this->belongsToMany('App\Models\Link', 'link_lists', 'list_id', 'link_id');
    }

    /*
     | ========================================================================
     | METHODS
     */

    /**
     * Get a collection of all lists for the current user, ordered by name
     *
     * @return Builder[]|Collection
     */
    public static function getAllForCurrentUser()
    {
        return self::byUser(auth()->id())
            ->orderBy('name')
            ->get();
    }

    /**
     * @param string|int $listId
     * @param string     $newName
     * @return bool
     */
    public static function nameHasChanged($listId, string $newName): bool
    {
        $oldName = self::find($listId)->name ?? null;
        return $oldName !== $newName;
    }
}
