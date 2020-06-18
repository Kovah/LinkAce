<?php

namespace App\Http\Controllers;

use App\Helper\UpdateHelper;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class FetchController extends Controller
{
    public function getTags(Request $request): JsonResponse
    {
        $query = $request->get('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $tags = Tag::byUser(auth()->user()->id)
            ->where('name', 'like', '%' . $query . '%')
            ->orderBy('name', 'asc')
            ->get();

        if (!$tags->isEmpty()) {
            // Properly format the results to be used by Selectize
            $tags = $tags->map(function ($item) {
                return [
                    'value' => $item->name,
                    'text' => $item->name,
                ];
            });
        }

        return response()->json($tags);
    }

    public function getLists(Request $request): JsonResponse
    {
        $query = $request->get('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $tags = LinkList::byUser(auth()->user()->id)
            ->where('name', 'like', '%' . $query . '%')
            ->orderBy('name', 'asc')
            ->get();

        if (!$tags->isEmpty()) {
            // Properly format the results to be used by Selectize
            $tags = $tags->map(function ($item) {
                return [
                    'value' => $item->name,
                    'text' => $item->name,
                ];
            });
        }

        return response()->json($tags);
    }

    public function searchExistingUrls(Request $request): JsonResponse
    {
        $query = $request->get('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $linkCount = Link::byUser(auth()->user()->id)
            ->where('url', trim($query))
            ->count();

        return response()->json(['linkFound' => $linkCount > 0]);
    }

    public function htmlForUrl(Request $request): Response
    {
        $url = $request->input('url');
        $response = Http::timeout(3)->get($url);

        if ($response->successful()) {
            return response($response->body());
        }

        return response(null);
    }

    public static function checkForUpdates(): JsonResponse
    {
        $updateCheck = UpdateHelper::checkForUpdates();

        return response()->json(['checkResult' => $updateCheck]);
    }
}
