<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

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
class User extends Authenticatable implements Auditable
{
    use AuditableTrait;
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
        'api_token',
    ];

    /*
     * ========================================================================
     * AUDIT SETTINGS
     */

    protected array $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    protected array $auditInclude = [
        'name',
        'email',
    ];

    public array $auditModifiers = [];

    /*
     * ========================================================================
     * RELATIONSHIPS
     */

    public function rawSettings(): HasMany
    {
        return $this->hasMany(Setting::class, 'user_id', 'id');
    }

    public function settings(): Collection
    {
        if ($this->rawSettings->isEmpty()) {
            $this->load('rawSettings');
        }

        return $this->rawSettings->mapWithKeys(fn($item) => [$item['key'] => $item['value']]);
    }
}
