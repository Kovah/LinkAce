<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Redactors\RightRedactor;

class UserInvitation extends Model implements Auditable
{
    use AuditableTrait;
    use Notifiable;

    protected $fillable = [
        'token',
        'email',
        'accepted_at',
        'inviter_id',
        'created_user_id',
        'valid_until',
    ];

    protected $hidden = [
        'token',
    ];

    /*
     * ========================================================================
     * AUDIT SETTINGS
     */

    protected array $auditEvents = [
        'created',
        'updated',
    ];

    protected array $auditInclude = [
        'email',
        'accepted_at',
        'inviter_id',
        'created_user_id',
        'valid_until',
    ];

    protected array $attributeModifiers = [
        'token' => RightRedactor::class,
    ];

    public array $auditModifiers = [];

    /*
     * ========================================================================
     * RELATIONS
     */

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }
}
