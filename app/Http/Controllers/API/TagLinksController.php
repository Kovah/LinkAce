<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagLinksController extends Controller
{
    /**
     * Get the links for a specific tag
     *
     * @param $tagID
     * @return JsonResponse
     */
    public function __invoke($tagID): JsonResponse
    {
        $tag = Tag::findOrFail($tagID);

        $links = $tag->links()->paginate(getPaginationLimit());

        return response()->json($links);
    }
}
