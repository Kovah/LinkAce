<?php

namespace App\Http\Controllers\Guest;

use App\Enums\ModelAttribute;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Controllers\Traits\ConfiguresLinkDisplay;
use App\Models\LinkList;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListController extends Controller
{
    use ChecksOrdering;
    use ConfiguresLinkDisplay;

    public function __construct()
    {
        $this->allowedOrderBy = LinkList::$allowOrderBy;
    }

    public function index(Request $request): View
    {
        $this->orderBy = $request->input('orderBy', 'created_at');
        $this->orderDir = $request->input('orderDir', 'desc');
        $this->checkOrdering();

        $lists = LinkList::publicOnly()
            ->withCount(['links' => fn($query) => $query->publicOnly()])
            ->orderBy($this->orderBy, $this->orderDir)
            ->paginate(getPaginationLimit());

        return view('guest.lists.index', [
            'pageTitle' => trans('list.lists'),
            'lists' => $lists,
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
        ]);
    }

    public function show(Request $request, LinkList $list): View
    {
        $this->updateLinkDisplayForGuest();

        if ($list->visibility !== ModelAttribute::VISIBILITY_PUBLIC) {
            abort(404);
        }

        $this->orderBy = $request->input('orderBy', 'created_at');
        $this->orderDir = $request->input('orderDir', 'desc');
        $this->checkOrdering();

        $links = $list->links()->publicOnly();

        if ($this->orderBy === 'random') {
            $links->inRandomOrder();
        } else {
            $links->orderBy($this->orderBy, $this->orderDir);
        }

        $links = $links->paginate(getPaginationLimit());

        return view('guest.lists.show', [
            'pageTitle' => trans('list.list') . ': ' . $list->name,
            'list' => $list,
            'links' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
        ]);
    }
}
