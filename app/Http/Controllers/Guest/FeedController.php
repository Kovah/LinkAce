<?php

namespace App\Http\Controllers\Guest;

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
        $links = Link::publicOnly()->latest()->with('user')->get();
        $meta = [
            'title' => 'LinkAce Links',
            'link' => $request->fullUrl(),
            'updated' => now()->toRfc3339String(),
            'id' => $request->fullUrl(),
        ];

        return new Response(view('guest.links.feed', [
            'meta' => $meta,
            'links' => $links,
        ]), 200, ['Content-Type' => 'application/xml']);
    }

    public function lists(Request $request)
    {
        $lists = LinkList::publicOnly()->latest()->with('user')->get();
        $meta = [
            'title' => 'LinkAce Lists',
            'link' => $request->fullUrl(),
            'updated' => now()->toRfc3339String(),
            'id' => $request->fullUrl(),
        ];

        return new Response(view('guest.lists.feed', [
            'meta' => $meta,
            'lists' => $lists,
        ]), 200, ['Content-Type' => 'application/xml']);
    }

    public function tags(Request $request)
    {
        $tags = Tag::publicOnly()->latest()->with('user')->get();
        $meta = [
            'title' => 'LinkAce Links',
            'link' => $request->fullUrl(),
            'updated' => now()->toRfc3339String(),
            'id' => $request->fullUrl(),
        ];

        return new Response(view('guest.tags.feed', [
            'meta' => $meta,
            'tags' => $tags,
        ]), 200, ['Content-Type' => 'application/xml']);
    }
}
