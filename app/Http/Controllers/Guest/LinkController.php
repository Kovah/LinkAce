<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Link;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('guest.links.index')
            ->with('links', Link::orderBy('created_at', 'DESC')
                ->private(false)
                ->paginate(getPaginationLimit())
            );
    }
}
