<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryDeleteRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('models.categories.index')
            ->with('categories', Category::byUser(auth()->user()->id)
                ->parentOnly()
                ->orderBy('name', 'ASC')
                ->paginate(config('linkace.default.pagination'))
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('models.categories.create')
            ->with('categories', Category::parentOnly()->orderBy('name', 'asc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryStoreRequest $request
     * @return
     */
    public function store(CategoryStoreRequest $request)
    {
        $data = $request->except(['tags', 'reload_view']);

        // Set the user ID
        $data['user_id'] = auth()->user()->id;

        $data['parent_category'] = isset($data['parent_category']) && $data['parent_category'] > 0 ?: null;

        // Create the new link
        $link = Category::create($data);

        alert(trans('category.added_successfully'), 'success');

        if ($request->get('reload_view')) {
            session()->flash('reload_view', true);
            return redirect()->route('categories.create');
        }

        return redirect()->route('categories.show', [$link->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (empty($category)) {
            abort(404);
        }

        return view('models.categories.show')
            ->with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $category = Category::find($id);

        if (empty($category)) {
            abort(404);
        }

        return view('models.categories.edit')
            ->with('categories', Category::parentOnly()->orderBy('name', 'asc')->get())
            ->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryUpdateRequest $request
     * @param  int                  $id
     * @return
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        $category = Category::find($id);

        if (empty($category)) {
            abort(404);
        }

        $data = $request->all();

        // Set the correct parent category
        $data['parent_category'] = isset($data['parent_category']) && $data['parent_category'] > 0 ?: null;

        // Update the existing category with new data
        $category->update($data);

        alert(trans('category.updated_successfully'), 'success');

        return redirect()->route('categories.show', [$category->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CategoryDeleteRequest $request
     * @param  int                  $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(CategoryDeleteRequest $request, $id)
    {
        $category = Category::find($id);

        if (empty($category)) {
            abort(404);
        }

        // Remove the category as a parent from all child categories
        if ($category->childCategories->count()) {
            foreach ($category->childCategories as $child) {
                $child->parent_category = null;
                $child->save();
            }
        }

        $category->delete();

        alert(trans('category.deleted_successfully'), 'warning');

        return redirect()->route('categories.index');
    }
}
