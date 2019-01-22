<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = Category::parentOnly();

        if ($request->has('orderBy') && $request->has('orderDir')) {
            $categories->orderBy($request->get('orderBy'), $request->get('orderDir'));
        } else {
            $categories->orderBy('name', 'ASC');
        }

        $categories = $categories->paginate(getPaginationLimit());

        return view('guest.categories.index', [
            'categories' => $categories,
            'route' => $request->fullUrl(),
            'order_by' => $request->get('orderBy'),
            'order_dir' => $request->get('orderDir'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  int    $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $category = Category::find($id);

        if (empty($category)) {
            abort(404);
        }

        // Get links of the category
        $links = $category->links()->privateOnly(false);

        if ($request->has('orderBy') && $request->has('orderDir')) {
            $links->orderBy($request->get('orderBy'), $request->get('orderDir'));
        } else {
            $links->orderBy('created_at', 'DESC');
        }

        $links = $links->paginate(getPaginationLimit());

        return view('guest.categories.show', [
            'category' => $category,
            'category_links' => $links,
            'route' => $request->getBaseUrl(),
            'order_by' => $request->get('orderBy'),
            'order_dir' => $request->get('orderDir'),
        ]);
    }
}
