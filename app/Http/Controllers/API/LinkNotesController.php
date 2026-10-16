<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Api\ApiLink;
use App\Models\Api\ApiNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinkNotesController extends Controller
{
    public function __invoke(Request $request, ApiLink $link): JsonResponse
    {
        if ($request->user()->cannot('view', $link)) {
            return response()->json(status: 403);
        }

        if ($request->user()->cannot('viewAny', ApiNote::class)) {
            return response()->json(status: 403);
        }

        $notes = $link->notes()->visibleForUser()->paginate(getPaginationLimit());

        return response()->json($notes);
    }
}
