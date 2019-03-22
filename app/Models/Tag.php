<?php

namespace App\Models;

use App\Scopes\OrderNameScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 * Class Tag
 *
 * @package App\Models
 * @property int                    $id
 * @property int                    $user_id
 * @property string                 $name
 * @property int                    $is_private
 * @property \Carbon\Carbon|null    $created_at
 * @property \Carbon\Carbon|null    $updated_at
 * @property string|null            $deleted_at
 * @property-read Collection|Link[] $links
 * @property-read User              $user
 * @method static Builder|Tag byUser($user_id)
 */
class Tag extends RememberedModel
{
    use SoftDeletes;

    public $table = 'tags';

    public $fillable = [
        'user_id',
        'name',
        'is_private',
    ];

    /**
     * Tag constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (useCacheTags()) {
            $this->rememberCacheTag = 'tag_queries';
        }

        parent::__construct($attributes);
    }

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function links()
    {
        return $this->belongsToMany('App\Models\Link', 'link_tags', 'tag_id', 'link_id');
    }

    /*
     | ========================================================================
     | METHODS
     */

    /**
     * Conditionally flush cache based on cache driver
     *
     * @return void
     */
    public static function flushCache()
    {
        if (useCacheTags()) {
            parent::flushCache();
        } else {
            Cache::flush();
        }
    }
}
