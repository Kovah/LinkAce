<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

/**
 * Class AjaxController
 *
 * @package App\Http\Controllers
 */
class AjaxController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTags(Request $request)
    {
        $query = $request->get('query', false);

        if (!$query) {
            return response()->json([]);
        }

        // Search for tags
        $tags = Tag::byUser(auth()->user()->id)
            ->where('name', 'like', '%' . $query . '%')
            ->orderBy('name', 'asc')
            ->get();

        if (!$tags->isEmpty()) {
            // Properly format the results
            $tags = $tags->map(function ($item) {
                return [
                    'value' => $item->name,
                    'text' => $item->name,
                ];
            });
        }

        return response()->json($tags);
    }
}
