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
    public function index(): View
    {
        $recentLinks = Link::query()
            ->visibleForUser()
            ->latest()
            ->limit(5)
            ->get();

        $recentTags = Tag::query()
            ->visibleForUser()
            ->with('user:id,name')
            ->latest()
            ->limit(25)
            ->get();

        $recentLists = LinkList::query()
            ->visibleForUser()
            ->with('user:id,name')
            ->latest()
            ->limit(15)
            ->get();

        $brokenLinks = Link::query()
            ->visibleForUser()
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
            'pageTitle' => trans('linkace.dashboard'),
            'recent_links' => $recentLinks,
            'recent_tags' => $recentTags,
            'recent_lists' => $recentLists,
            'stats' => $stats,
        ]);
    }
}
