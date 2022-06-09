<?php

namespace App\View\Components\History;

use App\Models\Link;
use Illuminate\View\Component;
use OwenIt\Auditing\Models\Audit;

class TagEntry extends Component
{
    public function __construct(private Audit $entry, private array $changes = [])
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

    protected function processChange(string $field, array $changeData): void
    {
        $fieldName = trans('tag.' . $field);
        [$oldValue, $newValue] = $this->processValues($field, $changeData);

        if ($oldValue === null) {
            $change = trans('linkace.history_added', [
                'fieldname' => $fieldName,
                'newvalue' => htmlspecialchars($newValue),
            ]);
        } elseif ($newValue === null) {
            $change = trans('linkace.history_removed', [
                'fieldname' => $fieldName,
                'oldvalue' => htmlspecialchars($oldValue),
            ]);
        } else {
            $change = trans('linkace.history_changed', [
                'fieldname' => $fieldName,
                'oldvalue' => htmlspecialchars($oldValue),
                'newvalue' => htmlspecialchars($newValue),
            ]);
        }

        $this->changes[] = $change;
    }

    /**
     * Apply specialized methods for different fields to handle particular
     * formatting needs of these fields.
     *
     * @param string $field
     * @param array  $changeData
     * @return array
     */
    protected function processValues(string $field, array $changeData): array
    {
        $oldValue = $changeData['old'] ?? null;
        $newValue = $changeData['new'] ?? null;

        /** @var Link $model */
        $model = app($this->entry->auditable_type);

        if (isset($model->auditModifiers[$field])) {
            $modifier = app($model->auditModifiers[$field]);
            $oldValue = $modifier->modify($oldValue);
            $newValue = $modifier->modify($newValue);
            return [$oldValue, $newValue];
        }

        return [$oldValue, $newValue];
    }
}
