<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;

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

    public function rawSettings(): HasMany
    {
        return $this->hasMany(Setting::class, 'user_id', 'id');
    }

    public function settings()
    {
        if ($this->rawSettings->isEmpty()) {
            $this->load('rawSettings');
        }

        return $this->rawSettings->mapWithKeys(function ($item) {
            return [$item['key'] => $item['value']];
        });
    }
}
