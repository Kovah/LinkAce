<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrashClearRequest;
use App\Http\Requests\TrashRestoreRequest;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use App\Repositories\TrashRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrashController extends Controller
{
    public function getLinks(Request $request): JsonResponse
    {
        $links = Link::onlyTrashed()
            ->byUser($request->user()->id)
            ->get();

        return response()->json($links->toArray());
    }

    public function getLists(Request $request): JsonResponse
    {
        $lists = LinkList::onlyTrashed()
            ->byUser($request->user()->id)
            ->get();

        return response()->json($lists);
    }

    public function getTags(Request $request): JsonResponse
    {
        $tags = Tag::onlyTrashed()
            ->byUser($request->user()->id)
            ->get();

        return response()->json($tags);
    }

    public function getNotes(Request $request): JsonResponse
    {
        $notes = Note::onlyTrashed()
            ->byUser($request->user()->id)
            ->get();

        return response()->json($notes);
    }

    /**
     * Permanently delete entries for a model from the trash.
     *
     * @param TrashClearRequest $request
     * @return JsonResponse
     */
    public function clear(TrashClearRequest $request): JsonResponse
    {
        TrashRepository::delete($request->input('model'));

        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * Restore an entry from the trash.
     *
     * @param TrashRestoreRequest $request
     * @return JsonResponse
     */
    public function restore(TrashRestoreRequest $request): JsonResponse
    {
        TrashRepository::restore($request->input('model'), $request->input('id'));

        return response()->json(null, Response::HTTP_OK);
    }
}
