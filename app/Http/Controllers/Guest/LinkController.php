<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\View\View;

class LinkController extends Controller
{
    /**
     * Display an overview of all links.
     *
     * @return View
     */
    public function index(): View
    {
        $links = Link::privateOnly(false)
            ->paginate(getPaginationLimit());

        return view('guest.links.index', [
            'links' => $links,
        ]);
    }
}
