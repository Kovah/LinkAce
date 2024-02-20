<?php

namespace App\Settings;

use App\Audits\Modifiers\BooleanModifier;
use App\Audits\Modifiers\DarkmodeSettingModifier;
use App\Audits\Modifiers\DisplayModeSettingModifier;
use App\Audits\Modifiers\LocaleSettingModifier;
use App\Audits\Modifiers\RedactedModifier;
use App\Audits\Modifiers\VisibilityModifier;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Redactors\LeftRedactor;

/**
 * Class AuditModel
 *
 * This model exists to audit changes in any settings.
 */
class SettingsAudit extends Model implements Auditable
{
    use AuditableTrait;

    public $table = 'settings';
    protected $fillable = ['id'];

    /*
     * ========================================================================
     * AUDIT SETTINGS
     */

    protected $auditInclude = [
        'group',
        'name',
        'payload',
    ];

    protected $auditEvents = [
        'updated',
    ];

    public array $auditModifiers = [
        // User & guest settings
        'archive_backups_enabled' => BooleanModifier::class,
        'archive_private_backups_enabled' => BooleanModifier::class,
        'darkmode_setting' => DarkmodeSettingModifier::class,
        'link_display_mode' => DisplayModeSettingModifier::class,
        'links_new_tab' => BooleanModifier::class,
        'links_default_visibility' => VisibilityModifier::class,
        'lists_default_visibility' => VisibilityModifier::class,
        'tags_default_visibility' => VisibilityModifier::class,
        'notes_default_visibility' => VisibilityModifier::class,
        'locale' => LocaleSettingModifier::class,
        'markdown_for_text' => BooleanModifier::class,
        'profile_is_public' => BooleanModifier::class,
        'share_service' => BooleanModifier::class,

        // System settings
        'guest_access_enabled' => BooleanModifier::class,
        'cron_token' => RedactedModifier::class,
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
        $key = $data['old_values']['name'];
        $data['old_values'][$key] = $data['old_values']['payload'];
        $data['new_values'][$key] = $data['new_values']['payload'];

        unset(
            $data['old_values']['name'],
            $data['new_values']['name'],
            $data['old_values']['payload'],
            $data['new_values']['payload']
        );

        return $data;
    }
}
