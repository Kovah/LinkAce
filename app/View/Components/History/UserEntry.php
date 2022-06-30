<?php

namespace App\View\Components\History;

use App\Models\User;
use Illuminate\View\Component;
use OwenIt\Auditing\Models\Audit;

class UserEntry extends Component
{
    use ProcessesHistory;

    private string $model = User::class;
    private string $translateBase = 'user';

    public function __construct(private Audit $entry)
    {
    }

    public function render()
    {
        $timestamp = formatDateTime($this->entry->created_at);

        if ($this->entry->event === 'deleted') {
            $this->changes[] = trans('user.history_deleted', ['name' => $this->entry->getModified()['name']['old']]);
        } elseif ($this->entry->event === 'restored') {
            $this->changes[] = trans('user.history_restored', ['name' => $this->entry->getModified()['name']['new']]);
        } elseif ($this->entry->event === 'created') {
            $this->changes[] = trans('user.history_created', ['name' => $this->entry->getModified()['name']['new']]);
        } elseif ($this->entry->event === 'blocked') {
            $this->changes[] = trans('user.history_blocked', ['name' => $this->entry->auditable->name]);
        } elseif ($this->entry->event === 'unblocked') {
            $this->changes[] = trans('user.history_unblocked', ['name' => $this->entry->auditable->name]);
        } else {
            foreach ($this->entry->getModified() as $field => $change) {
                $this->processChange($field, $change);

                $this->changes = array_map(function ($change) {
                    return trans('audit.user_history_entry', [
                        'id' => $this->entry->auditable->id,
                        'change' => $change,
                    ]);
                }, $this->changes);
            }
        }

        return view('components.history-entry', [
            'timestamp' => $timestamp,
            'changes' => $this->changes,
        ]);
    }
}
