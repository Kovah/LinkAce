<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LinkList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListLinksController extends Controller
{
    public function __invoke(Request $request, LinkList $list): JsonResponse
    {
        if ($request->user()->cannot('view', $list)) {
            return response()->json(status: 403);
        }

        $links = $list->links()->paginate(getPaginationLimit());

        return response()->json($links);
    }
}
