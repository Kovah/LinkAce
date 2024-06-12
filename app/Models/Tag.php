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
 * Class Tag
 *
 * @package App\Models
 * @property int                    $id
 * @property int                    $user_id
 * @property string                 $name
 * @property int                    $is_private
 * @property Carbon|null            $created_at
 * @property Carbon|null            $updated_at
 * @property string|null            $deleted_at
 * @property-read Collection|Link[] $links
 * @property-read User              $user
 * @method static Builder|Tag byUser(int $user_id = null)
 * @method static Builder|Tag publicOnly()
 * @method static Builder|Tag privateOnly()
 */
class Tag extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'tags';

    public $fillable = [
        'user_id',
        'name',
        'is_private',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'is_private' => 'boolean',
    ];

    /**
     * @param string|int $tagId
     * @param string     $newName
     * @return bool
     */
    public static function nameHasChanged($tagId, string $newName): bool
    {
        $oldName = self::find($tagId)->name ?? null;
        return $oldName !== $newName;
    }

    /**
     * Override inherited function such that it finds existing tags case-insensitively, but creates them with the right casing
     *
     * @param array $attributes
     * @param array $values
     * @return Builder
     */
    public static function firstOrCreate(array $attributes = [], array $values = []): self
    {
        if (isset($attributes['name'])) {
            // SQL lower() function is checked to be supported by MariaDB, SQLite, PostgreSQL, and MSSQL
            $existing = self::whereRaw('lower(name) = lower(?)', [$attributes['name']])->first();
            if (!is_null($existing)) {
                return $existing;
            }
        }

        if (!is_null($instance = self::where($attributes)->first())) {
            return $instance;
        }

        return self::createOrFirst($attributes, $values);
    }

    /*
     | ========================================================================
     | SCOPES
     */

    /**
     * Add the OrderNameScope to the Tag model
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderNameScope());
    }

    /**
     * Scope for the user relation
     *
     * @param Builder  $query
     * @param int|null $user_id
     * @return Builder
     */
    public function scopeByUser(Builder $query, int $user_id = null): Builder
    {
        if (is_null($user_id) && auth()->check()) {
            $user_id = auth()->id();
        }
        return $query->where('user_id', $user_id);
    }

    /**
     * Scope for selecting private tags only
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePrivateOnly(Builder $query): Builder
    {
        return $query->where('is_private', true);
    }

    /*
     | ========================================================================
     | RELATIONSHIPS
     */

    /**
     * Scope for selecting public tags only
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublicOnly(Builder $query): Builder
    {
        return $query->where('is_private', false);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /*
     | ========================================================================
     | METHODS
     */

    /**
     * @return BelongsToMany
     */
    public function links(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Link', 'link_tags', 'tag_id', 'link_id');
    }
}
