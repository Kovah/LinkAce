<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HandlesQueryOrder;
use App\Models\LinkList;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListController extends Controller
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
        $lists = LinkList::publicOnly()
            ->withCount('links')
            ->orderBy(
                $request->input('orderBy', 'name'),
                $this->getOrderDirection($request, 'asc')
            )
            ->paginate(getPaginationLimit());

        return view('guest.lists.index', [
            'pageTitle' => trans('list.lists'),
            'lists' => $lists,
            'orderBy' => $request->input('orderBy', 'name'),
            'orderDir' => $this->getOrderDirection($request, 'asc'),
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
                $this->getOrderDirection($request, 'asc')
            )->paginate(getPaginationLimit());

        return view('guest.lists.show', [
            'pageTitle' => trans('list.list') . ': ' . $list->name,
            'list' => $list,
            'listLinks' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'title'),
            'orderDir' => $this->getOrderDirection($request, 'asc'),
        ]);
    }
}
