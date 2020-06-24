<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\TagDeleteRequest;
use App\Http\Requests\Models\TagStoreRequest;
use App\Http\Requests\Models\TagUpdateRequest;
use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $tags = Tag::byUser(auth()->id())
            ->orderBy(
                $request->input('order_by', 'created_at'),
                $request->input('order_dir', 'DESC')
            )
            ->paginate(getPaginationLimit());

        return response()->json($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TagStoreRequest $request
     * @return JsonResponse
     */
    public function store(TagStoreRequest $request): JsonResponse
    {
        $tag = TagRepository::create($request->all());

        return response()->json($tag);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $tag = Tag::findOrFail($id);

        return response()->json($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TagUpdateRequest $request
     * @param int              $id
     * @return JsonResponse
     */
    public function update(TagUpdateRequest $request, $id): JsonResponse
    {
        $tag = Tag::findOrFail($id);

        $updatedTag = TagRepository::update($tag, $request->all());

        return response()->json($updatedTag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TagDeleteRequest $request
     * @param int              $id
     * @return JsonResponse
     */
    public function destroy(TagDeleteRequest $request, $id): JsonResponse
    {
        $tag = Tag::findOrFail($id);

        $deletionSuccessfull = TagRepository::delete($tag);

        if ($deletionSuccessfull) {
            return response()->json(null, Response::HTTP_OK);
        }

        return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
