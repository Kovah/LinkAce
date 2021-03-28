<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    public function links(Request $request)
    {
        $links = Link::latest()->with('user')->get();
        $meta = [
            'title' => 'LinkAce Links',
            'link' => $request->fullUrl(),
            'updated' => now()->toRfc3339String(),
            'id' => $request->fullUrl(),
        ];

        return new Response(view('actions.feed.links', [
            'meta' => $meta,
            'links' => $links,
        ]), 200, ['Content-Type' => 'application/xml']);
    }

    public function lists(Request $request)
    {
        $lists = LinkList::latest()->with('user')->get();
        $meta = [
            'title' => 'LinkAce Lists',
            'link' => $request->fullUrl(),
            'updated' => now()->toRfc3339String(),
            'id' => $request->fullUrl(),
        ];

        return new Response(view('actions.feed.lists', [
            'meta' => $meta,
            'lists' => $lists,
        ]), 200, ['Content-Type' => 'application/xml']);
    }

    public function tags(Request $request)
    {
        $tags = Tag::latest()->with('user')->get();
        $meta = [
            'title' => 'LinkAce Links',
            'link' => $request->fullUrl(),
            'updated' => now()->toRfc3339String(),
            'id' => $request->fullUrl(),
        ];

        return new Response(view('actions.feed.tags', [
            'meta' => $meta,
            'tags' => $tags,
        ]), 200, ['Content-Type' => 'application/xml']);
    }
}
