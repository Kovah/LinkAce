<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LinkList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListLinksController extends Controller
{
    /**
     * Get the links for a specific list.
     *
     * @param LinkList $list
     * @return JsonResponse
     */
    public function __invoke(Request $request, LinkList $list): JsonResponse
    {
        if ($request->user()->cannot('view', $list)) {
            return response()->json([], 403);
        }

        $links = $list->links()->paginate(getPaginationLimit());

        return response()->json($links);
    }
}
