<?php

namespace App\View\Components\History;

use Illuminate\View\Component;
use Spatie\Activitylog\Models\Activity;

class ActivityEntry extends Component
{
    private array $changes = [];
    private string $translateBase = 'audit.logs';

    public function __construct(private Activity $activity)
    {
    }

    public function render()
    {
        $timestamp = formatDateTime($this->activity->created_at);

        $this->processActivity();

        return view('components.history-entry', [
            'timestamp' => $timestamp,
            'changes' => $this->changes,
        ]);
    }

    protected function processActivity(): void
    {
        $change = trans($this->translateBase . '.' . $this->activity->description);

        if ($this->activity->causer() !== null) {
            $this->changes[] = trans('audit.activity_entry_with_causer', [
                'change' => $change,
                'causer' => $this->activity->causer?->name ?: trans('user.unknown_user'),
            ]);
            return;
        }

        $this->changes[] = $change;
    }
}
