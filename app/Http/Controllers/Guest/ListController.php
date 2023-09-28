<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Models\LinkList;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListController extends Controller
{
    use ChecksOrdering;

    public function __construct()
    {
        $this->allowedOrderBy = LinkList::$allowOrderBy;
    }

    public function index(Request $request): View
    {
        $this->orderBy = $request->input('orderBy', 'created_at');
        $this->orderDir = $request->input('orderBy', 'desc');
        $this->checkOrdering();

        $lists = LinkList::publicOnly()
            ->withCount(['links' => fn($query) => $query->publicOnly()])
            ->orderBy($this->orderBy, $this->orderDir)
            ->paginate(getPaginationLimit());

        return view('guest.lists.index', [
            'lists' => $lists,
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
        ]);
    }

    public function show(Request $request, int $listID): View
    {
        $list = LinkList::publicOnly()->findOrFail($listID);

        $this->orderBy = $request->input('orderBy', 'created_at');
        $this->orderDir = $request->input('orderBy', 'desc');
        $this->checkOrdering();

        $links = $list->links()
            ->publicOnly()
            ->orderBy($this->orderBy, $this->orderDir)
            ->paginate(getPaginationLimit());

        return view('guest.lists.show', [
            'list' => $list,
            'listLinks' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
        ]);
    }
}
