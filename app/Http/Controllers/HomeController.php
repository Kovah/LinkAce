<?php

namespace App\Http\Controllers;

use App\Models\Link;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recent_links = Link::byUser(auth()->user()->id)
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        return view('home')
            ->with('recent_links', $recent_links);
    }
}
