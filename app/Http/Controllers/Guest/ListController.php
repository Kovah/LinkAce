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
     * @return View
     */
    public function index(): View
    {
        $lists = LinkList::isPrivate(false)
            ->paginate(getPaginationLimit());

        return view('guest.lists.index', [
            'lists' => $lists,
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
                $request->input('orderDir', 'ASC')
            )->paginate(getPaginationLimit());

        return view('guest.lists.show', [
            'list' => $list,
            'list_links' => $links,
            'route' => $request->getBaseUrl(),
            'order_by' => $request->input('orderBy', 'title'),
            'order_dir' => $request->input('orderDir', 'ASC'),
        ]);
    }
}
