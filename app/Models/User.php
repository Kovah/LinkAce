<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @package App\Models
 * @property int                 $id
 * @property string              $name
 * @property string              $email
 * @property string              $password
 * @property string|null         $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
     | ========================================================================
     | RELATIONSHIPS
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rawSettings()
    {
        return $this->hasMany(Setting::class, 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function settings()
    {
        return $this->rawSettings->mapWithKeys(function ($item) {
            return [$item['key'] => $item['value']];
        });
    }
}
