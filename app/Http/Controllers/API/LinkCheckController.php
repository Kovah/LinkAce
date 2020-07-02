<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinkCheckController extends Controller
{
    /**
     * Search for a link based on a given url.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $searchedUrl = $request->input('url', false);

        if (!$searchedUrl) {
            return response()->json(['linksFound' => false]);
        }

        $linkCount = Link::byUser($request->user()->id)
            ->where('url', trim($searchedUrl))
            ->count();

        return response()->json(['linksFound' => $linkCount > 0]);
    }
}
