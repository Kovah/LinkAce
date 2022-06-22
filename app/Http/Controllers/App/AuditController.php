<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Settings\SettingsAudit;
use OwenIt\Auditing\Models\Audit;
use Spatie\Activitylog\Models\Activity;

class AuditController extends Controller
{
    public function __invoke()
    {
        $activities = Activity::query()->with('causer')->latest()->paginate(pageName: 'activities_page');

        $settingsHistory = Audit::where('auditable_type', SettingsAudit::class)->with('auditable')
            ->latest()->paginate(pageName: 'settings_page');

        $userHistory = Audit::where('auditable_type', User::class)->with('auditable')
            ->latest()->paginate(pageName: 'user_page');

        return view('app.audit-logs', [
            'activities' => $activities,
            'settings_history' => $settingsHistory,
            'user_history' => $userHistory,
        ]);
    }
}
