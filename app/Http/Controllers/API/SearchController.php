<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SearchesLinks;
use App\Http\Requests\SearchRequest;
use App\Search\SearchBackendManager;
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
        $links = $this->searchLinkResults($request);

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
        $tags = app(SearchBackendManager::class)->backend()->searchTags($request);

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
        $tags = app(SearchBackendManager::class)->backend()->searchLists($request);

        return response()->json($tags);
    }
}
