<?php

namespace App\View\Components\Links;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\Component;
use Illuminate\View\View;
use Venturecraft\Revisionable\Revision;

/**
 * Class HistoryEntry
 *
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
 * with these IDs are returned. There entries are then joined into a comma
 * separated string, ready to be displayed.
 *
 * @package App\View\Components\Links
 */
class HistoryEntry extends Component
{
    /** @var Revision */
    private $entry;

    public function __construct(Revision $entry)
    {
        $this->entry = $entry;
    }

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
                'newvalue' => htmlspecialchars($newValue),
            ]);
        } elseif ($newValue === null) {
            $change = trans('link.history_removed', [
                'fieldname' => $fieldname,
                'oldvalue' => htmlspecialchars($oldValue),
            ]);
        } else {
            $change = trans('link.history_changed', [
                'fieldname' => $fieldname,
                'oldvalue' => htmlspecialchars($oldValue),
                'newvalue' => htmlspecialchars($newValue),
            ]);
        }

        return view('components.links.history-entry', [
            'timestamp' => $timestamp,
            'change' => $change,
        ]);
    }

    /**
     * Apply specialized methods for different fields to handle particular
     * formatting needs of these fields.
     *
     * @return array
     */
    protected function processValues(): array
    {
        $oldValue = $this->entry->oldValue();
        $newValue = $this->entry->newValue();

        if ($this->entry->fieldName() === Link::REV_TAGS_NAME) {
            return $this->processTagsField($oldValue, $newValue);
        }

        if ($this->entry->fieldName() === Link::REV_LISTS_NAME) {
            return $this->processListsField($oldValue, $newValue);
        }

        if ($this->entry->fieldName() === 'is_private') {
            return $this->processPrivateField($oldValue, $newValue);
        }

        if ($this->entry->fieldName() === 'status') {
            return $this->processStatusField($oldValue, $newValue);
        }

        return [$this->entry->oldValue(), $this->entry->newValue()];
    }

    /**
     * Tags are queried from the database based on the given IDs and then joined
     * into one comma separated string.
     *
     * @param $oldValue
     * @param $newValue
     * @return null[]|string[]
     */
    protected function processTagsField($oldValue, $newValue): array
    {
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

    /**
     * Lists are queried from the database based on the given IDs and then
     * joined into one comma separated string.
     *
     * @param $oldValue
     * @param $newValue
     * @return array
     */
    protected function processListsField($oldValue, $newValue): array
    {
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

    /**
     * The Private field is a boolean field, thus needs to be formatted with
     * yes and no values.
     *
     * @param $oldValue
     * @param $newValue
     * @return array
     */
    protected function processPrivateField($oldValue, $newValue): array
    {
        $oldValue = $oldValue ? trans('linkace.yes') : trans('linkace.no');
        $newValue = $newValue ? trans('linkace.yes') : trans('linkace.no');

        return [$oldValue, $newValue];
    }

    /**
     * The Status field is a mapped constant field, thus needs to be formatted with
     * the correct translated values.
     *
     * @param $oldValue
     * @param $newValue
     * @return array
     */
    protected function processStatusField($oldValue, $newValue): array
    {
        $oldValue = trans('link.stati.' . $oldValue);
        $newValue = trans('link.stati.' . $newValue);

        return [$oldValue, $newValue];
    }

    /**
     * The deleted field displays its own string based on whether the link
     * was deleted or restored.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    protected function processDeletedField()
    {
        $change = $this->entry->oldValue() === null ? trans('link.history_deleted') : trans('link.history_restored');

        return view('components.links.history-entry', [
            'timestamp' => formatDateTime($this->entry->created_at),
            'change' => $change,
        ]);
    }
}
