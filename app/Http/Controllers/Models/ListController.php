<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\ListDeleteRequest;
use App\Http\Requests\Models\ListStoreRequest;
use App\Http\Requests\Models\ListUpdateRequest;
use App\Models\LinkList;
use App\Repositories\ListRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $lists = LinkList::byUser(auth()->id())
            ->orderBy(
                $request->input('orderBy', 'name'),
                $request->input('orderDir', 'ASC')
            )
            ->paginate(getPaginationLimit());

        return view('models.lists.index', [
            'lists' => $lists,
            'route' => $request->getBaseUrl(),
            'order_by' => $request->input('orderBy', 'name'),
            'order_dir' => $request->input('orderDir', 'ASC'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('models.lists.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ListStoreRequest $request
     * @return RedirectResponse
     */
    public function store(ListStoreRequest $request): RedirectResponse
    {
        $data = $request->except(['reload_view']);

        $list = ListRepository::create($data);

        flash(trans('list.added_successfully'), 'success');

        if ($request->input('reload_view')) {
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
     * @return View
     */
    public function show(Request $request, $id): View
    {
        $list = LinkList::findOrFail($id);

        $links = $list->links()->byUser(auth()->id());

        if ($request->has('orderBy') && $request->has('orderDir')) {
            $links->orderBy($request->input('orderBy'), $request->input('orderDir'));
        } else {
            $links->orderBy('created_at', 'DESC');
        }

        $links = $links->paginate(getPaginationLimit());

        return view('models.lists.show', [
            'list' => $list,
            'list_links' => $links,
            'route' => $request->getBaseUrl(),
            'order_by' => $request->input('orderBy'),
            'order_dir' => $request->input('orderDir'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function edit($id): View
    {
        $list = LinkList::findOrFail($id);

        return view('models.lists.edit')->with('list', $list);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ListUpdateRequest $request
     * @param int               $id
     * @return RedirectResponse
     */
    public function update(ListUpdateRequest $request, $id): RedirectResponse
    {
        $list = LinkList::findOrFail($id);

        $list = ListRepository::update($list, $request->all());

        flash(trans('list.updated_successfully'), 'success');

        return redirect()->route('lists.show', [$list->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ListDeleteRequest $request
     * @param int               $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(ListDeleteRequest $request, $id): RedirectResponse
    {
        $list = LinkList::findOrFail($id);

        $deletionSuccessfull = ListRepository::delete($list);

        if (!$deletionSuccessfull) {
            flash(trans('list.deletion_error'), 'error');
            return redirect()->back();
        }

        flash(trans('list.deleted_successfully'), 'warning');
        return redirect()->route('lists.index');
    }
}
