<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Settings\SettingsAudit;
use OwenIt\Auditing\Models\Audit;
use Spatie\Activitylog\Models\Activity;

class AuditController extends Controller
{
    public function __invoke()
    {
        $activities = Activity::query()->with('causer')->latest()->paginate(25, pageName: 'activities_page');

        $settingsHistory = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')
            ->latest()->paginate(25, pageName: 'settings_page');

        $userHistory = Audit::where('auditable_type', User::class)->with('auditable')
            ->latest()->paginate(25, pageName: 'user_page');

        return view('admin.audit-logs', [
            'activities' => $activities,
            'settings_history' => $settingsHistory,
            'user_history' => $userHistory,
        ]);
    }
}
