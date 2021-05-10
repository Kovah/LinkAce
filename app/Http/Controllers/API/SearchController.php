<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SearchesLinks;
use App\Http\Requests\SearchRequest;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use SearchesLinks;

    /**
     * Search links with the help of the SearchesLinks trait, which offers the
     * same search features like in the web app:
     * - toggle searching by title
     * - toggle searching by description
     * - toggle searching private links only
     * - toggle searching broken links only
     * - search by lists
     * - search by tags
     * - order the results by various parameters
     *
     * @param SearchRequest $request
     * @return JsonResponse
     */
    public function searchLinks(SearchRequest $request): JsonResponse
    {
        $search = $this->buildDatabaseQuery($request);
        $links = $search->paginate(getPaginationLimit());

        return response()->json($links);
    }

    /**
     * Search tags by query for usage in link editing. To catch as many as
     * possible tags for a query, a LIKE operation with `%[query]%` is run.
     * Tags are returned as a simple array with tag id => tag name pairs.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchByTags(Request $request): JsonResponse
    {
        $query = $request->input('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $tags = Tag::byUser($request->user()->id)
            ->where('name', 'like', '%' . $query . '%')
            ->orderBy('name')
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
    public function searchByLists(Request $request): JsonResponse
    {
        $query = $request->input('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $tags = LinkList::byUser($request->user()->id)
            ->where('name', 'like', '%' . $query . '%')
            ->orderBy('name')
            ->pluck('name', 'id');

        return response()->json($tags);
    }
}
