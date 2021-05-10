<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\LinkList;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
        $lists = LinkList::publicOnly()
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
     * @param int     $listID
     * @return View
     */
    public function show(Request $request, int $listID): View
    {
        $list = LinkList::publicOnly()->findOrFail($listID);

        $links = $list->links()
            ->publicOnly()
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
