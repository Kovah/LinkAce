<?php

namespace App\View\Components\History;

use App\Models\Tag;
use Illuminate\View\Component;
use OwenIt\Auditing\Models\Audit;

class TagEntry extends Component
{
    use ProcessesHistory;

    private string $model = Tag::class;
    private string $translateBase = 'tag';

    public function __construct(private Audit $entry)
    {
    }

    public function render()
    {
        $timestamp = formatDateTime($this->entry->created_at);

        if ($this->entry->event === 'deleted') {
            $this->changes[] = trans('tag.history_deleted');
        } elseif ($this->entry->event === 'restored') {
            $this->changes[] = trans('tag.history_restored');
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
