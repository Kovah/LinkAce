<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\LinkList;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function index()
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $list = LinkList::find($id);

        if (empty($list)) {
            abort(404);
        }

        $links = $list->links()->privateOnly(false);

        if ($request->has('orderBy') && $request->has('orderDir')) {
            $links->orderBy($request->get('orderBy'), $request->get('orderDir'));
        } else {
            $links->orderBy('created_at', 'DESC');
        }

        $links = $links->paginate(getPaginationLimit());

        return view('guest.lists.show', [
            'list' => $list,
            'list_links' => $links,
            'route' => $request->getBaseUrl(),
            'order_by' => $request->get('orderBy'),
            'order_dir' => $request->get('orderDir'),
        ]);
    }
}
