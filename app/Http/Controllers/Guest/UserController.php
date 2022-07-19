<?php

namespace App\Http\Controllers\Guest;

use App\Enums\ModelAttribute;
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
        if (usersettings('profile_is_public', $user->id) === false) {
            abort(404);
        }

        $links = Link::byUser($user->id)
            ->whereVisibility(ModelAttribute::VISIBILITY_PUBLIC)
            ->latest()
            ->take(10)
            ->paginate(pageName: 'link_page');

        $lists = LinkList::byUser($user->id)
            ->whereVisibility(ModelAttribute::VISIBILITY_PUBLIC)
            ->latest()
            ->take(10)
            ->paginate(pageName: 'link_page');

        $tags = Tag::byUser($user->id)
            ->whereVisibility(ModelAttribute::VISIBILITY_PUBLIC)
            ->latest()
            ->take(10)
            ->paginate(pageName: 'link_page');

        $stats = [
            'total_links' => Link::byUser($user->id)->whereVisibility(ModelAttribute::VISIBILITY_PUBLIC)->count(),
            'total_lists' => LinkList::byUser($user->id)->whereVisibility(ModelAttribute::VISIBILITY_PUBLIC)->count(),
            'total_tags' => Tag::byUser($user->id)->whereVisibility(ModelAttribute::VISIBILITY_PUBLIC)->count(),
            'total_notes' => Note::byUser($user->id)->whereVisibility(ModelAttribute::VISIBILITY_PUBLIC)->count(),
        ];

        return view('guest.users.show', [
            'user' => $user,
            'links' => $links,
            'lists' => $lists,
            'tags' => $tags,
            'stats' => $stats,
        ]);
    }
}
