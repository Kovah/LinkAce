<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HandlesQueryOrder;
use App\Models\Link;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    use HandlesQueryOrder;

    /**
     * Display an overview of all links.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $links = Link::publicOnly()
            ->with('tags');

        $orderBy = $request->input('orderBy', 'created_at');
        $orderDir = $this->getOrderDirection($request);
        if ($orderBy === 'random') {
            $links->inRandomOrder();
        } else {
            $links->orderBy($orderBy, $orderDir);
        }

        return view('guest.links.index', [
            'pageTitle' => trans('link.links'),
            'links' => $links->paginate(getPaginationLimit()),
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'created_at'),
            'orderDir' => $request->input('orderDir', 'desc'),
        ]);
    }
}
