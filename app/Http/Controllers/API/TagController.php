<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\TagDeleteRequest;
use App\Http\Requests\Models\TagStoreRequest;
use App\Http\Requests\Models\TagUpdateRequest;
use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tags = Tag::byUser(auth()->id())
            ->orderBy(
                $request->get('order_by', 'created_at'),
                $request->get('order_dir', 'DESC')
            )
            ->paginate(getPaginationLimit());

        return response()->json($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TagStoreRequest $request
     * @return Response
     */
    public function store(TagStoreRequest $request)
    {
        $tag = TagRepository::create($request->all());

        return response()->json($tag);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $tag = Tag::findOrFail($id);

        return response()->json($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TagUpdateRequest $request
     * @param int              $id
     * @return Response
     */
    public function update(TagUpdateRequest $request, $id)
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
     * @return Response
     */
    public function destroy(TagDeleteRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $deletionSuccessfull = TagRepository::delete($tag);

        if ($deletionSuccessfull) {
            return response(null, Response::HTTP_OK);
        }

        return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
