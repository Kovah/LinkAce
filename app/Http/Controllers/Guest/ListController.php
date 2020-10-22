<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\LinkList;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListController extends Controller
{
    /**
     * Display an overview of all lists.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $lists = LinkList::isPrivate(false)
            ->withCount('links')
            ->orderBy(
                $request->input('orderBy', 'name'),
                $request->input('orderDir', 'asc')
            )
            ->paginate(getPaginationLimit());

        return view('guest.lists.index', [
            'lists' => $lists,
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
        $list = LinkList::isPrivate(false)->findOrFail($id);

        $links = $list->links()
            ->privateOnly(false)
            ->orderBy(
                $request->input('orderBy', 'title'),
                $request->input('orderDir', 'asc')
            )->paginate(getPaginationLimit());

        return view('guest.lists.show', [
            'list' => $list,
            'listLinks' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'title'),
            'orderDir' => $request->input('orderDir', 'asc'),
        ]);
    }
}
