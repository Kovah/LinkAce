<?php

namespace App\View\Components\Links;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\View\Component;
use Illuminate\View\View;
use Venturecraft\Revisionable\Revision;

class HistoryEntry extends Component
{
    /** @var Revision */
    private $entry;

    public function __construct(Revision $entry)
    {
        $this->entry = $entry;
    }

    /**
     * This view component renders a changeset from a history entry as complex
     * logic is needed to properly display all different cases.
     * First, if the deleted_at field is detected, the component will output
     * either a "was deleted" or "was restored" changeset.
     *
     * For all other fields, the proper change is generated:
     * - If no old value is present, a field value was added.
     * - If no new value is present, a field value was removed.
     * - Otherwise a field was changed.
     *
     * Tags and lists are treated separately, as the history entry will only
     * store the corresponding IDs. In the Link model, custom attribute
     * accessors (getRevtagsAttribute) will return a collection of all related
     * entries. E.g. if the revtags history field contains "12,13,48", the tags
     * with these IDs are returned. These entries are
     *
     * @return View|string
     */
    public function render()
    {
        if ($this->entry->fieldName() === 'deleted_at') {
            return $this->processDeletedField();
        }

        $timestamp = formatDateTime($this->entry->created_at);
        $fieldname = trans('link.' . $this->entry->fieldName());

        [$oldValue, $newValue] = $this->processValues();

        if ($oldValue === null) {
            $change = trans('link.history_added', [
                'fieldname' => $fieldname,
                'newvalue' => $newValue,
            ]);
        } elseif ($newValue === null) {
            $change = trans('link.history_removed', [
                'fieldname' => $fieldname,
                'oldvalue' => $oldValue,
            ]);
        } else {
            $change = trans('link.history_changed', [
                'fieldname' => $fieldname,
                'oldvalue' => $oldValue,
                'newvalue' => $newValue,
            ]);
        }

        return view('components.links.history-entry', [
            'timestamp' => $timestamp,
            'change' => $change,
        ]);
    }

    /**
     * @return array|null[]
     */
    protected function processValues()
    {
        $oldValue = $this->entry->oldValue();
        $newValue = $this->entry->newValue();

        if ($this->entry->fieldName() === Link::REV_TAGS_NAME) {
            $oldTags = $oldValue
                ? Tag::whereIn('id', explode(',', $oldValue))
                    ->pluck('name')->join(', ')
                : null;

            $newTags = $newValue
                ? Tag::whereIn('id', explode(',', $newValue))
                    ->pluck('name')->join(', ')
                : null;

            return [$oldTags, $newTags];
        }

        if ($this->entry->fieldName() === Link::REV_LISTS_NAME) {
            $oldTags = $oldValue
                ? LinkList::whereIn('id', explode(',', $oldValue))
                    ->pluck('name')->join(', ')
                : null;

            $newTags = $newValue ?
                LinkList::whereIn('id', explode(',', $newValue))
                    ->pluck('name')->join(', ')
                : null;

            return [$oldTags, $newTags];
        }

        return [$this->entry->oldValue(), $this->entry->newValue()];
    }

    protected function processDeletedField()
    {
        $change = $this->entry->oldValue() === null ? trans('link.history_deleted') : trans('link.history_restored');

        return view('components.links.history-entry', [
            'timestamp' => formatDateTime($this->entry->created_at),
            'change' => $change,
        ]);
    }
}
