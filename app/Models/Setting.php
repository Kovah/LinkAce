<?php

namespace App\Models;

use App\Audits\Modifiers\BooleanModifier;
use App\Audits\Modifiers\DarkmodeSettingModifier;
use App\Audits\Modifiers\DisplayModeSettingModifier;
use App\Audits\Modifiers\LocaleSettingModifier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class Setting
 *
 * @package App\Models
 * @property int    $id
 * @property int    $user_id
 * @property string $key
 * @property mixed  $value
 * @method static Builder|Setting byUser($user_id = null)
 * @method static Builder|Setting systemOnly()
 */
class Setting extends Model implements Auditable
{
    use AuditableTrait;

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
     * ========================================================================
     * AUDIT SETTINGS
     */

    protected $auditEvents = [
        'updated',
    ];

    public array $auditModifiers = [
        'archive_backups_enabled' => BooleanModifier::class,
        'archive_private_backups_enabled' => BooleanModifier::class,
        'darkmode_setting' => DarkmodeSettingModifier::class,
        'link_display_mode' => DisplayModeSettingModifier::class,
        'links_new_tab' => BooleanModifier::class,
        'links_private_default' => BooleanModifier::class,
        'lists_private_default' => BooleanModifier::class,
        'locale' => LocaleSettingModifier::class,
        'markdown_for_text' => BooleanModifier::class,
        'notes_private_default' => BooleanModifier::class,
        'private_default' => BooleanModifier::class,
        'share_service' => BooleanModifier::class,
        'system_guest_access' => BooleanModifier::class,
        'tags_private_default' => BooleanModifier::class,
    ];

    /**
     * Instead of having 'value' as the changed field, use the actual settings
     * key as the changed field.
     *
     * @param array $data
     * @return array
     */
    public function transformAudit(array $data): array
    {
        $keys = self::getSettingKeys();
        $key = $keys[$data['auditable_id']];

        $data['old_values'][$key] = $data['old_values']['value'];
        $data['new_values'][$key] = $data['new_values']['value'];
        unset($data['old_values']['value'], $data['new_values']['value']);

        return $data;
    }

    /*
     * ========================================================================
     * SCOPES
     */

    /**
     * Scope for the user relation
     *
     * @param Builder  $query
     * @param int|null $user_id
     * @return Builder
     */
    public function scopeByUser(Builder $query, int $user_id = null): Builder
    {
        if (is_null($user_id) && auth()->check()) {
            $user_id = auth()->id();
        }
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

    /*
     * ========================================================================
     * RELATIONSHIPS
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
     * ========================================================================
     * METHODS
     */

    public static function getSettingKeys()
    {
        return Cache::rememberForever('settings_keys', fn() => Setting::get()->pluck('key', 'id'));
    }
}
