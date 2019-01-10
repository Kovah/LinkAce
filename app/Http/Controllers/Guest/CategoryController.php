<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
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
        return view('guest.categories.index')
            ->with('categories', Category::parentOnly()
                ->orderBy('name', 'ASC')
                ->paginate(getPaginationLimit())
            );
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

        return view('guest.categories.show')
            ->with('category', $category)
            ->with('category_links', $category->links()
                ->private(false)
                ->paginate(getPaginationLimit())
            );
    }
}
