<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers\App
 */
class DashboardController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $recent_links = Link::byUser(auth()->user()->id)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        $broken_links = Link::byUser(auth()->user()->id)
            ->where('status', '>', 1)
            ->count();

        $stats = [
            'total_links' => Link::count(),
            'total_lists' => LinkList::count(),
            'total_tags' => Tag::count(),
            'total_notes' => Note::count(),
            'total_broken_links' => $broken_links,
        ];

        return view('dashboard')
            ->with('recent_links', $recent_links)
            ->with('stats', $stats);
    }
}
