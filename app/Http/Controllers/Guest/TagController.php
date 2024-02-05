<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HandlesQueryOrder;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use HandlesQueryOrder;

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
                $this->getOrderDirection($request, 'asc')
            )
            ->paginate(getPaginationLimit());

        return view('guest.tags.index', [
            'pageTitle' => trans('tag.tags'),
            'tags' => $tags,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'name'),
            'orderDir' => $this->getOrderDirection($request, 'asc'),
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
            ->publicOnly()
            ->orderBy(
                $request->input('orderBy', 'title'),
                $this->getOrderDirection($request, 'asc')
            )->paginate(getPaginationLimit());

        return view('guest.tags.show', [
            'pageTitle' => trans('tag.tag') . ': ' . $tag->name,
            'tag' => $tag,
            'tagLinks' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'title'),
            'orderDir' => $request->input('orderDir', 'asc'),
        ]);
    }
}
