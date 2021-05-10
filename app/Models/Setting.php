<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @package App\Models
 * @property int    $id
 * @property int    $user_id
 * @property string $key
 * @property mixed  $value
 * @method static Builder|Setting byUser($user_id)
 * @method static Builder|Setting systemOnly()
 */
class Setting extends Model
{
    public $table = 'settings';

    public $timestamps = false;

    public $fillable = [
        'user_id',
        'key',
        'value',
    ];

    protected $casts = [
        'user_id' => 'integer',
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
     * @return Builder
     */
    public function scopeByUser(Builder $query, int $user_id): Builder
    {
        return $query->where('user_id', $user_id);
    }

    /**
     * Scope to get system settings only
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeSystemOnly(Builder $query): Builder
    {
        return $query->whereNull('user_id');
    }
}
