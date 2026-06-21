<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        $links = Link::visibleForUser()
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->paginate(pageName: 'link_page');

        $lists = LinkList::visibleForUser()
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->paginate(pageName: 'link_page');

        $tags = Tag::visibleForUser()
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->paginate(pageName: 'link_page');

        $stats = [
            'total_links' => Link::visibleForUser()->where('user_id', $user->id)->count(),
            'total_lists' => LinkList::visibleForUser()->where('user_id', $user->id)->count(),
            'total_tags' => Tag::visibleForUser()->where('user_id', $user->id)->count(),
            'total_notes' => Note::visibleForUser()->where('user_id', $user->id)->count(),
        ];

        return view('models.users.show', [
            'user' => $user,
            'links' => $links,
            'lists' => $lists,
            'tags' => $tags,
            'stats' => $stats,
        ]);
    }
}
