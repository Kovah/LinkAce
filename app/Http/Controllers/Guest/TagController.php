<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
        $tags = Tag::publicOnly()
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
     * @param int     $tagID
     * @return View
     */
    public function show(Request $request, int $tagID): View
    {
        $tag = Tag::publicOnly()->findOrFail($tagID);

        $links = $tag->links()
            ->privateOnly()
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
