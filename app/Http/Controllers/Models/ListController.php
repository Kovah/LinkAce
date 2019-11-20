<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListDeleteRequest;
use App\Http\Requests\ListStoreRequest;
use App\Http\Requests\ListUpdateRequest;
use App\Models\LinkList;
use App\Repositories\ListRepository;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $lists = LinkList::byUser(auth()->id())
            ->paginate(getPaginationLimit());

        return view('models.lists.index', [
            'lists' => $lists,
            'route' => $request->getBaseUrl(),
            'order_by' => $request->get('orderBy'),
            'order_dir' => $request->get('orderDir'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('models.lists.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ListStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ListStoreRequest $request)
    {
        $data = $request->except(['reload_view']);

        $list = ListRepository::create($data);

        alert(trans('list.added_successfully'), 'success');

        if ($request->get('reload_view')) {
            session()->flash('reload_view', true);
            return redirect()->route('lists.create');
        }

        return redirect()->route('lists.show', [$list->id]);
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

        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        $links = $list->links()->byUser(auth()->id());

        if ($request->has('orderBy') && $request->has('orderDir')) {
            $links->orderBy($request->get('orderBy'), $request->get('orderDir'));
        } else {
            $links->orderBy('created_at', 'DESC');
        }

        $links = $links->paginate(getPaginationLimit());

        return view('models.lists.show', [
            'list' => $list,
            'list_links' => $links,
            'route' => $request->getBaseUrl(),
            'order_by' => $request->get('orderBy'),
            'order_dir' => $request->get('orderDir'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $list = LinkList::find($id);

        if (empty($list)) {
            abort(404);
        }

        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        return view('models.lists.edit')->with('list', $list);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ListUpdateRequest $request
     * @param int               $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ListUpdateRequest $request, $id)
    {
        $list = LinkList::find($id);

        if (empty($list)) {
            abort(404);
        }

        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->all();
        $list = ListRepository::update($list, $data);

        alert(trans('list.updated_successfully'), 'success');

        return redirect()->route('lists.show', [$list->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ListDeleteRequest $request
     * @param int               $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(ListDeleteRequest $request, $id)
    {
        $list = LinkList::find($id);

        if (empty($list)) {
            abort(404);
        }

        $deletion_successfull = ListRepository::delete($list);

        if (!$deletion_successfull) {
            alert(trans('list.deletion_error'), 'error');
            return redirect()->back();
        }

        alert(trans('list.deleted_successfully'), 'warning');
        return redirect()->route('lists.index');
    }
}
