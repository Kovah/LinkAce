<?php

namespace App\Listeners;

use App\Settings\SettingsAudit;
use Illuminate\Support\Facades\Event;
use OwenIt\Auditing\Events\AuditCustom;
use Spatie\LaravelSettings\Events\SavingSettings;

class SavingSettingsListener
{
    public function handle(SavingSettings $event): void
    {
        $group = $event->settings::group();
        $changes = array_diff_assoc($event->properties->toArray(), $event->originalValues->toArray());

        foreach ($changes as $key => $change) {
            $id = SettingsAudit::query()->where(['group' => $group, 'name' => $key])->first()->id;
            $setting = (new SettingsAudit(['id' => $id, 'group' => $group, 'name' => $key]));

            $setting->auditEvent = 'updated';
            $setting->isCustomEvent = true;
            $setting->auditCustomOld = [
                'name' => $key,
                'payload' => $event->originalValues->get($key),
            ];
            $setting->auditCustomNew = [
                'name' => $key,
                'payload' => $change,
            ];

            Event::dispatch(AuditCustom::class, [$setting]);
        }
    }
}
