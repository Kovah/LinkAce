<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinkNotesController extends Controller
{
    /**
     * Get the notes for a specific link.
     *
     * @param Link $link
     * @return JsonResponse
     */
    public function __invoke(Request $request, Link $link): JsonResponse
    {
        if ($request->user()->cannot('view', $link)) {
            return response()->json([], 403);
        }

        $notes = $link->notes()->visibleForUser()->paginate(getPaginationLimit());

        return response()->json($notes);
    }
}
