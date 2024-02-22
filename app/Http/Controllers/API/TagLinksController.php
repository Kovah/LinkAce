<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagLinksController extends Controller
{
    public function __invoke(Request $request, Tag $tag): JsonResponse
    {
        if ($request->user()->cannot('view', $tag)) {
            return response()->json(status: 403);
        }

        $links = $tag->links()->visibleForUser()->paginate(getPaginationLimit());

        return response()->json($links);
    }
}
