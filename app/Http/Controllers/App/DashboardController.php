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
        $recentLinks = Link::query()
            ->visibleForUser()
            ->latest()
            ->limit(5)
            ->get();

        $recentTags = Tag::query()
            ->visibleForUser()
            ->latest()
            ->limit(25)
            ->get();

        $recentLists = LinkList::query()
            ->visibleForUser()
            ->latest()
            ->limit(15)
            ->get();

        $brokenLinks = Link::query()
            ->visibleForUser()
            ->where('status', '>', 1)
            ->count();

        $stats = [
            'total_links' => Link::count(),
            'total_lists' => LinkList::count(),
            'total_tags' => Tag::count(),
            'total_notes' => Note::count(),
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
