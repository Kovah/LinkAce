<?php

namespace App\View\Components\History;

use App\Models\LinkList;
use Illuminate\View\Component;
use OwenIt\Auditing\Models\Audit;

class ListEntry extends Component
{
    use ProcessesHistory;

    private string $model = LinkList::class;
    private string $translateBase = 'list';

    public function __construct(private Audit $entry)
    {
    }

    public function render()
    {
        $timestamp = formatDateTime($this->entry->created_at);

        if ($this->entry->event === 'deleted') {
            $this->changes[] = trans('list.history_deleted');
        } elseif ($this->entry->event === 'restored') {
            $this->changes[] = trans('list.history_restored');
        } else {
            foreach ($this->entry->getModified() as $field => $change) {
                $this->processChange($field, $change);
            }
        }

        return view('components.history-entry', [
            'timestamp' => $timestamp,
            'changes' => $this->changes,
        ]);
    }
}
