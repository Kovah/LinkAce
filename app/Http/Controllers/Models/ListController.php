<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\ListStoreRequest;
use App\Http\Requests\Models\ListUpdateRequest;
use App\Models\LinkList;
use App\Repositories\ListRepository;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            ->withCount('links')
            ->orderBy(
                $request->input('orderBy', 'name'),
                $request->input('orderDir', 'asc')
            )
            ->paginate(getPaginationLimit());

        return view('models.lists.index', [
            'lists' => $lists,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'name'),
            'orderDir' => $request->input('orderDir', 'asc'),
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
     * @param Request  $request
     * @param LinkList $list
     * @return View
     */
    public function show(Request $request, LinkList $list): View
    {
        $links = $list->links()
            ->byUser(auth()->id())
            ->orderBy(
                $request->input('orderBy', 'created_at'),
                $request->input('orderDir', 'desc')
            )->paginate(getPaginationLimit());

        return view('models.lists.show', [
            'list' => $list,
            'listLinks' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'created_at'),
            'orderDir' => $request->input('orderDir', 'desc'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param LinkList $list
     * @return View
     */
    public function edit(LinkList $list): View
    {
        return view('models.lists.edit', ['list' => $list]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ListUpdateRequest $request
     * @param LinkList          $list
     * @return RedirectResponse
     */
    public function update(ListUpdateRequest $request, LinkList $list): RedirectResponse
    {
        $list = ListRepository::update($list, $request->all());

        flash(trans('list.updated_successfully'), 'success');
        return redirect()->route('lists.show', [$list->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param LinkList $list
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(LinkList $list): RedirectResponse
    {
        $deletionSuccessful = ListRepository::delete($list);

        if (!$deletionSuccessful) {
            flash(trans('list.deletion_error'), 'error');
            return redirect()->back();
        }

        flash(trans('list.deleted_successfully'), 'warning');
        return redirect()->route('lists.index');
    }
}
