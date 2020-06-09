<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search tags by query for usage in link editing. To catch as many as
     * possible tags for a query, a LIKE operation with `%[query]%` is run.
     * Tags are returned as a simple array with tag id => tag name pairs.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchTags(Request $request): JsonResponse
    {
        $query = $request->input('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $tags = Tag::byUser($request->user()->id)
            ->where('name', 'like', '%' . $query . '%')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        return response()->json($tags);
    }

    /**
     * Search lists by query for usage in link editing. To catch as many as
     * possible lists for a query, a LIKE operation with `%[query]%` is run.
     * Tags are returned as a simple array with list id => list name pairs.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchLists(Request $request): JsonResponse
    {
        $query = $request->input('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $tags = LinkList::byUser($request->user()->id)
            ->where('name', 'like', '%' . $query . '%')
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        return response()->json($tags);
    }
}
