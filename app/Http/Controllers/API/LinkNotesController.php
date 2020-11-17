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
     * @param Link $link
     * @return JsonResponse
     */
    public function __invoke(Link $link): JsonResponse
    {
        $notes = $link->notes()->paginate(getPaginationLimit());

        return response()->json($notes);
    }
}
