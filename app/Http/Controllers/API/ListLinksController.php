<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LinkList;
use Illuminate\Http\JsonResponse;

class ListLinksController extends Controller
{
    /**
     * Get the links for a specific list
     *
     * @param $listID
     * @return JsonResponse
     */
    public function __invoke($listID): JsonResponse
    {
        $list = LinkList::findOrFail($listID);

        $links = $list->links()->paginate(getPaginationLimit());

        return response()->json($links);
    }
}
