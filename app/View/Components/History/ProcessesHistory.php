<?php

namespace App\View\Components\History;

trait ProcessesHistory
{
    protected array $changes = [];

    protected function processChange(string $field, array $changeData): void
    {
        $fieldName = trans($this->translateBase . '.' . $field);
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

        $model = app($this->model);

        if (isset($model->auditModifiers[$field])) {
            $modifier = app($model->auditModifiers[$field]);
            $oldValue = $modifier->modify($oldValue);
            $newValue = $modifier->modify($newValue);
            return [$oldValue, $newValue];
        }

        return [$oldValue, $newValue];
    }
}
