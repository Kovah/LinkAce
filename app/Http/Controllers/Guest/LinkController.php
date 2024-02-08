<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Controllers\Traits\ConfiguresLinkDisplay;
use App\Models\Link;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    use ChecksOrdering;
    use ConfiguresLinkDisplay;

    public function __construct()
    {
        $this->allowedOrderBy = Link::$allowOrderBy;
    }

    public function index(Request $request): View
    {
        $this->updateLinkDisplayForGuest();

        $links = Link::publicOnly()->with(['tags' => fn ($query) => $query->publicOnly()]);

        $this->orderBy = $request->input('orderBy', 'created_at');
        $this->orderDir = $request->input('orderBy', 'desc');
        $this->checkOrdering();

        if ($this->orderBy === 'random') {
            $links->inRandomOrder();
        } else {
            $links->orderBy($this->orderBy, $this->orderDir);
        }

        return view('guest.links.index', [
            'pageTitle' => trans('link.links'),
            'links' => $links->paginate(getPaginationLimit()),
            'route' => $request->getBaseUrl(),
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
        ]);
    }
}
