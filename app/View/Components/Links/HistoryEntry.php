<?php

namespace App\View\Components\Links;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\View\Component;
use OwenIt\Auditing\Models\Audit;

/**
 * Class HistoryEntry
 *
 * This view component renders a changeset from a history entry as complex
 * logic is needed to properly display all different cases.
 * First it is checked if the entity was deleted or restored, which will shorten
 * the processing. Otherwise, the modified fields are processed.
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
    public function __construct(private Audit $entry, private array $changes = [])
    {
    }

    public function render()
    {
        $timestamp = formatDateTime($this->entry->created_at);

        if ($this->entry->event === 'deleted') {
            $this->changes[] = trans('link.history_deleted');
        } elseif ($this->entry->event === 'restored') {
            $this->changes[] = trans('link.history_restored');
        } else {
            foreach ($this->entry->getModified() as $field => $change) {
                $this->processChange($field, $change);
            }
        }

        return view('components.links.history-entry', [
            'timestamp' => $timestamp,
            'changes' => $this->changes,
        ]);
    }

    protected function processChange(string $field, array $changeData): void
    {
        $fieldName = trans('link.' . $field);
        [$oldValue, $newValue] = $this->processValues($field, $changeData);

        if ($oldValue === null) {
            $change = trans('link.history_added', [
                'fieldname' => $fieldName,
                'newvalue' => htmlspecialchars($newValue),
            ]);
        } elseif ($newValue === null) {
            $change = trans('link.history_removed', [
                'fieldname' => $fieldName,
                'oldvalue' => htmlspecialchars($oldValue),
            ]);
        } else {
            $change = trans('link.history_changed', [
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

        if ($field === Link::AUDIT_TAGS_NAME) {
            return $this->processTagsField($oldValue, $newValue);
        }

        if ($field === Link::AUDIT_LISTS_NAME) {
            return $this->processListsField($oldValue, $newValue);
        }

        if ($field === 'is_private') {
            return $this->processPrivateField($oldValue, $newValue);
        }

        if ($field === 'status') {
            return $this->processStatusField($oldValue, $newValue);
        }

        return [$oldValue, $newValue];
    }

    /**
     * Tags are queried from the database based on the given IDs and then joined
     * into one comma separated string.
     *
     * @param $oldValue
     * @param $newValue
     * @return array
     */
    protected function processTagsField($oldValue, $newValue): array
    {
        $oldTags = $oldValue
            ? Tag::whereIn('id', $oldValue)->pluck('name')->join(', ')
            : null;

        $newTags = $newValue
            ? Tag::whereIn('id', $newValue)->pluck('name')->join(', ')
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
            ? LinkList::whereIn('id', $oldValue)->pluck('name')->join(', ')
            : null;

        $newTags = $newValue ?
            LinkList::whereIn('id', $newValue)->pluck('name')->join(', ')
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
}
