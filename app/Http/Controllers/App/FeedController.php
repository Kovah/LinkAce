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
    public function links(Request $request): Response
    {
        $links = Link::latest()->with('user')->get();
        $meta = [
            'title' => 'LinkAce Links',
            'link' => $request->fullUrl(),
            'updated' => now()->toRfc3339String(),
            'id' => $request->fullUrl(),
        ];

        return new Response(view('app.feed.links', [
            'meta' => $meta,
            'links' => $links,
        ]), 200, ['Content-Type' => 'application/xml']);
    }

    public function lists(Request $request): Response
    {
        $lists = LinkList::latest()->with('user')->get();
        $meta = [
            'title' => 'LinkAce Lists',
            'link' => $request->fullUrl(),
            'updated' => now()->toRfc3339String(),
            'id' => $request->fullUrl(),
        ];

        return new Response(view('app.feed.lists', [
            'meta' => $meta,
            'lists' => $lists,
        ]), 200, ['Content-Type' => 'application/xml']);
    }

    public function listLinks(Request $request, LinkList $list): Response
    {
        $links = $list->links()->with('user')->latest()->get();
        $meta = [
            'title' => $list->name,
            'link' => $request->fullUrl(),
            'updated' => now()->toRfc3339String(),
            'id' => $request->fullUrl(),
        ];

        return new Response(view('app.feed.links', [
            'meta' => $meta,
            'links' => $links,
        ]), 200, ['Content-Type' => 'application/xml']);
    }

    public function tags(Request $request): Response
    {
        $tags = Tag::latest()->with('user')->get();
        $meta = [
            'title' => 'LinkAce Links',
            'link' => $request->fullUrl(),
            'updated' => now()->toRfc3339String(),
            'id' => $request->fullUrl(),
        ];

        return new Response(view('app.feed.tags', [
            'meta' => $meta,
            'tags' => $tags,
        ]), 200, ['Content-Type' => 'application/xml']);
    }

    public function tagLinks(Request $request, Tag $tag): Response
    {
        $links = $tag->links()->with('user')->latest()->get();
        $meta = [
            'title' => $tag->name,
            'link' => $request->fullUrl(),
            'updated' => now()->toRfc3339String(),
            'id' => $request->fullUrl(),
        ];

        return new Response(view('app.feed.links', [
            'meta' => $meta,
            'links' => $links,
        ]), 200, ['Content-Type' => 'application/xml']);
    }
}
