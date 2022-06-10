<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function __invoke()
    {
        $settingsHistory = Audit::where('auditable_type', Setting::class)->with('auditable')
            ->latest()->paginate(pageName: 'settings_page');

        $userHistory = Audit::where('auditable_type', User::class)->with('auditable')
            ->latest()->paginate(pageName: 'user_page');

        return view('app.audit-logs', [
            'settings_history' => $settingsHistory,
            'user_history' => $userHistory,
        ]);
    }
}
