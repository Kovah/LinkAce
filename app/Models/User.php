<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * @package App\Models
 * @property int         $id
 * @property string      $name
 * @property string      $email
 * @property string      $password
 * @property string|null $remember_token
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_secret
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable implements Auditable
{
    use AuditableTrait;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'blocked_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'blocked_at' => 'datetime',
    ];

    public string $langBase = 'user';

    /*
     * ========================================================================
     * AUDIT SETTINGS
     */

    public const AUDIT_BLOCK_EVENT = 'blocked';
    public const AUDIT_UNBLOCK_EVENT = 'unblocked';

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
     * SCOPES
     */

    public function scopeNotSystem(Builder $query): Builder
    {
        return $query->whereNot('id', 0);
    }

    /*
     * ========================================================================
     * METHODS
     */

    public static function getSystemUser(): User
    {
        return self::whereId(0)->first();
    }

    public function isBlocked(): bool
    {
        return $this->blocked_at !== null;
    }

    public function isCurrentlyLoggedIn(): bool
    {
        return $this->is(auth()->user());
    }

    public function isSystemUser(): bool
    {
        return $this->id === 0;
    }
}
