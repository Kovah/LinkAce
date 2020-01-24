<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

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

    public static function validateRegistration(array $data): ValidatorContract
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:10|confirmed',
        ]);
    }

    public static function createUser(array $data): self
    {
        return self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

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
