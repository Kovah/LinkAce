<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @package App\Models
 * @property int    $id
 * @property int    $user_id
 * @property string $key
 * @property mixed  $value
 * @method static \Illuminate\Database\Eloquent\Builder|Setting byUser($user_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting systemOnly()
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

    /*
     | ========================================================================
     | SCOPES
     */

    /**
     * Scope for the user relation
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param int                                    $user_id
     * @return mixed
     */
    public function scopeByUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /**
     * Scope to get system settings only
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function scopeSystemOnly($query)
    {
        return $query->whereNull('user_id');
    }
}
