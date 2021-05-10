<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * Class User
 *
 * @package App\Models
 * @property int         $id
 * @property string      $name
 * @property string      $email
 * @property string      $password
 * @property string|null $remember_token
 * @property string|null $api_token
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_secret
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
     | ========================================================================
     | RELATIONSHIPS
     */

    public function rawSettings(): HasMany
    {
        return $this->hasMany(Setting::class, 'user_id', 'id');
    }

    public function settings(): \Illuminate\Support\Collection
    {
        if ($this->rawSettings->isEmpty()) {
            $this->load('rawSettings');
        }

        return $this->rawSettings->mapWithKeys(function ($item) {
            return [$item['key'] => $item['value']];
        });
    }
}
