<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\JsonResponse;

class LinkNotesController extends Controller
{
    /**
     * Get the notes for a specific link.
     *
     * @param $linkID
     * @return JsonResponse
     */
    public function __invoke($linkID): JsonResponse
    {
        $link = Link::findOrFail($linkID);

        $notes = $link->notes()->paginate(getPaginationLimit());

        return response()->json($notes);
    }
}
