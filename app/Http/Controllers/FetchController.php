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
    /**
     * Returns all tags that match a given query, preformatted for Selectize.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTags(Request $request): JsonResponse
    {
        $query = $request->input('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $tags = Tag::byUser(auth()->user()->id)
            ->where('name', 'like', '%' . escapeSearchQuery($query) . '%')
            ->orderBy('name')
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

    /**
     * Returns all lists that match a given query, preformatted for Selectize.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getLists(Request $request): JsonResponse
    {
        $query = $request->input('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $tags = LinkList::byUser(auth()->user()->id)
            ->where('name', 'like', '%' . escapeSearchQuery($query) . '%')
            ->orderBy('name')
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

    /**
     * Returns a boolean flag which indicates that there already is a link
     * present for the given URL.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchExistingUrls(Request $request): JsonResponse
    {
        $query = $request->input('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $linkCount = Link::byUser(auth()->user()->id)
            ->where('url', trim($query))
            ->count();

        return response()->json(['linkFound' => $linkCount > 0]);
    }

    /**
     * Simple endpoint for the system settings page which runs an update check
     * and returns the result to the frontend.
     *
     * @return JsonResponse
     */
    public static function checkForUpdates(): JsonResponse
    {
        $updateCheck = UpdateHelper::checkForUpdates(true);

        return response()->json(['checkResult' => $updateCheck]);
    }

    /**
     * Returns the HTML for a given URL to prevent CORS issues in the frontend
     * implementation.
     *
     * @param Request $request
     * @return Response
     */
    public function htmlForUrl(Request $request): Response
    {
        $request->validate([
            'url' => ['url'],
        ]);

        $url = $request->input('url');
        $response = Http::timeout(3)->get($url);

        if ($response->successful()) {
            return response($response->body());
        }

        return response(null);
    }
}
