<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

trait ProvidesTaxonomyOutput
{
    public function oldTaxonomyOutput(string $taxonomy): bool|string
    {
        $old = self::getOldTaxonomyItems($taxonomy);

        if ($old->isNotEmpty()) {
            return json_encode($old);
        }

        return json_encode($this->prepareExistingTaxonomy($this->$taxonomy));
    }

    public static function oldTaxonomyOutputWithoutLink(string $taxonomy, mixed $default = []): bool|string
    {
        $old = self::getOldTaxonomyItems($taxonomy);

        return json_encode($old->isNotEmpty() ? $old : $default);
    }

    protected function prepareExistingTaxonomy(EloquentCollection $items): Collection
    {
        if ($items->isNotEmpty()) {
            $items->load('user:id,name');
        }

        return $items;
    }

    /*
     * To be able to correctly display old lists and tags inside the selects, we
     * need to get the original items from the database and return them.
     * If an item does not exist, it is returned as a generic item with the
     * name as the value.
     * Otherwise, the select would only show the tag and list IDs.
     */
    public static function getOldTaxonomyItems(string $taxonomy): Collection
    {
        $model = match ($taxonomy) {
            'tags', 'only_tags' => Tag::class,
            'lists', 'only_lists' => LinkList::class,
        };

        $data = collect();
        if ($old = old($taxonomy, false)) {
            $items = explode(',', $old);

            foreach ($items as $item) {
                if ((int)$item > 0) {
                    $item = $model::find($item)?->load('user:id,name');
                } else {
                    $item = [
                        'id' => $item,
                        'name' => $item,
                    ];
                }
                $data->push($item);
            }
        }

        return $data;
    }
}
