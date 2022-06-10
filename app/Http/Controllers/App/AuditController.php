<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function __invoke()
    {
        $settingsHistory = Audit::where('auditable_type', Setting::class)->with('auditable')
            ->latest()->paginate(pageName: 'settings');

        return view('app.audit-logs', [
            'settings_history' => $settingsHistory,
        ]);
    }
}
