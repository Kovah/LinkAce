<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    /**
     * @param array $data
     * @return Category
     */
    public static function create(array $data): Category
    {
        $data['user_id'] = auth()->user()->id;

        $data['parent_category'] = isset($data['parent_category']) && $data['parent_category'] > 0
            ? $data['parent_category']
            : null;

        return Category::create($data);
    }

    /**
     * @param Category $category
     * @param array    $data
     * @return Category
     */
    public static function update(Category $category, array $data): Category
    {
        $data['parent_category'] = isset($data['parent_category']) && $data['parent_category'] > 0
            ? $data['parent_category']
            : null;

        $category->update($data);

        return $category;
    }

    /**
     * @param Category $category
     * @return bool
     */
    public static function delete(Category $category): bool
    {
        if ($category->childCategories->count()) {
            $category->childCategories->each(function (Category $child) {
                $child->parent_category = null;
                $child->save();
            });
        }

        try {
            $category->delete();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
