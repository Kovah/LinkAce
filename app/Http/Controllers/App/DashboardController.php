<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard including all widgets.
     *
     * @return View
     */
    public function index(): View
    {
        $recentLinks = Link::byUser()
            ->latest()
            ->limit(5)
            ->get();

        $recentTags = Tag::byUser()
            ->latest()
            ->limit(25)
            ->get();

        $recentLists = LinkList::byUser()
            ->latest()
            ->limit(15)
            ->get();

        $brokenLinks = Link::byUser()
            ->where('status', '>', 1)
            ->count();

        $totalLinks = Link::byUser()
            ->count();

        $totalLists = LinkList::byUser()
            ->count();

        $totalNotes = Note::byUser()
            ->count();

        $totalTags = Tag::byUser()
            ->count();

        $stats = [
            'total_links' => $totalLinks,
            'total_lists' => $totalLists,
            'total_tags' => $totalTags,
            'total_notes' => $totalNotes,
            'total_broken_links' => $brokenLinks,
        ];

        return view('dashboard', [
            'recent_links' => $recentLinks,
            'recent_tags' => $recentTags,
            'recent_lists' => $recentLists,
            'stats' => $stats,
        ]);
    }
}
