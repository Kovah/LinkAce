<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Api\ApiLink;
use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinkCheckController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $searchedUrl = $request->input('url', false);

        $this->authorize('viewAny', ApiLink::class);

        if (!$searchedUrl) {
            return response()->json(['linksFound' => false]);
        }

        $linkCount = Link::query()
            ->visibleForUser()
            ->where('url', trim($searchedUrl))
            ->count();

        return response()->json(['linksFound' => $linkCount > 0]);
    }
}
