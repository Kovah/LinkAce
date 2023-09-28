<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Controllers\Traits\HandlesQueryOrder;
use App\Http\Requests\Models\ListStoreRequest;
use App\Http\Requests\Models\ListUpdateRequest;
use App\Models\LinkList;
use App\Repositories\ListRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ListController extends Controller
{
    use ChecksOrdering;
    use HandlesQueryOrder;

    public function __construct()
    {
        $this->allowedOrderBy = LinkList::$allowOrderBy;
        $this->authorizeResource(LinkList::class, 'list');
    }

    public function index(Request $request): View
    {
        $orderBy = $request->input('orderBy', session()->get('lists.index.orderBy', 'name'));
        $orderDir = $this->getOrderDirection($request, session()->get('lists.index.orderDir', 'asc'));
        $this->checkOrdering();

        session()->put('lists.index.orderBy', $orderBy);
        session()->put('lists.index.orderDir', $orderDir);

        $lists = LinkList::query()
            ->visibleForUser()
            ->withCount('links')
            ->orderBy($this->orderBy, $this->orderDir);

        if ($request->input('filter')) {
            $lists = $lists->where('name', 'like', '%' . $request->input('filter') . '%');
        }

        $lists = $lists->paginate(getPaginationLimit());

        return view('models.lists.index', [
            'lists' => $lists,
            'route' => $request->getBaseUrl(),
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
        ]);
    }

    public function create(): View
    {
        return view('models.lists.create');
    }

    public function store(ListStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $list = ListRepository::create($data);

        flash(trans('list.added_successfully'), 'success');

        if ($request->input('reload_view')) {
            return redirect()->route('lists.create')->with('reload_view', true);
        }

        return redirect()->route('lists.show', ['list' => $list]);
    }

    public function show(Request $request, LinkList $list): View
    {
        $links = $list->links()
            ->byUser()
            ->orderBy(
                $request->input('orderBy', 'created_at'),
                $request->input('orderDir', 'desc')
            )->paginate(getPaginationLimit());

        return view('models.lists.show', [
            'list' => $list,
            'history' => $list->audits()->latest()->get(),
            'listLinks' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'created_at'),
            'orderDir' => $request->input('orderDir', 'desc'),
        ]);
    }

    public function edit(LinkList $list): View
    {
        return view('models.lists.edit', ['list' => $list]);
    }

    public function update(ListUpdateRequest $request, LinkList $list): RedirectResponse
    {
        $list = ListRepository::update($list, $request->validated());

        flash(trans('list.updated_successfully'), 'success');
        return redirect()->route('lists.show', ['list' => $list]);
    }

    public function destroy(LinkList $list): RedirectResponse
    {
        $deletionSuccessful = ListRepository::delete($list);

        if (!$deletionSuccessful) {
            flash(trans('list.deletion_error'), 'error');
            return redirect()->back();
        }

        flash(trans('list.deleted_successfully'), 'warning');
        return request()->has('redirect_back') ? redirect()->back() : redirect()->route('lists.index');
    }
}
