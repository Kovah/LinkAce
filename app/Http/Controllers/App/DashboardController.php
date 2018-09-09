<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Link;

class DashboardController extends Controller
{
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

        return view('dashboard')
            ->with('recent_links', $recent_links);
    }
}
