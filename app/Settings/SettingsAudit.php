<?php

namespace App\Settings;

use App\Audits\Modifiers\BooleanModifier;
use App\Audits\Modifiers\DarkmodeSettingModifier;
use App\Audits\Modifiers\DisplayModeSettingModifier;
use App\Audits\Modifiers\LocaleSettingModifier;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

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
