<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
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

    protected $casts = [
        'valid_until' => 'datetime',
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
        return $this->belongsTo(User::class, 'inviter_id')->withTrashed();
    }

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_user_id')->withTrashed();
    }

    /*
     * ========================================================================
     * METHODS
     */

    public function inviteUrl(): string
    {
        return URL::temporarySignedRoute('auth.accept-invite', $this->valid_until, ['token' => $this->token]);
    }

    public function isValid(): bool
    {
        return $this->valid_until->gt(now())
            && $this->accepted_at === null
            && $this->created_user_id === null;
    }

    /**
     * Mark the invitation as used by the given user. The invitation is claimed
     * with a single conditional update, so a concurrent request which already
     * claimed it affects no rows and gets false in return.
     */
    public function consumeFor(User $user): bool
    {
        $claimed = static::whereKey($this->getKey())
            ->whereNull('accepted_at')
            ->whereNull('created_user_id')
            ->update(['accepted_at' => now()]);

        if ($claimed !== 1) {
            return false;
        }

        // Save the created user through the model, so the change ends up in
        // the audit log as well
        $this->refresh();
        $this->created_user_id = $user->id;
        $this->save();

        return true;
    }

    public function isCompleted(): bool
    {
        return $this->created_user_id !== null;
    }
}
