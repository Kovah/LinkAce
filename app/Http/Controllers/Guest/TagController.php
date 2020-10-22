<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display an overview of all lists.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $tags = Tag::isPrivate(false)
            ->withCount('links')
            ->orderBy(
                $request->input('orderBy', 'name'),
                $request->input('orderDir', 'asc')
            )
            ->paginate(getPaginationLimit());

        return view('guest.tags.index', [
            'tags' => $tags,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'name'),
            'orderDir' => $request->input('orderDir', 'asc'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int     $id
     * @return View
     */
    public function show(Request $request, $id): View
    {
        $tag = Tag::isPrivate(false)->findOrFail($id);

        $links = $tag->links()
            ->privateOnly(false)
            ->orderBy(
                $request->input('orderBy', 'title'),
                $request->input('orderDir', 'ASC')
            )->paginate(getPaginationLimit());

        return view('guest.tags.show', [
            'tag' => $tag,
            'tagLinks' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'title'),
            'orderDir' => $request->input('orderDir', 'ASC'),
        ]);
    }
}
