<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Requests\Models\TagStoreRequest;
use App\Http\Requests\Models\TagUpdateRequest;
use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TagController extends Controller
{
    use ChecksOrdering;

    protected array $allowedOrders = [
        'id',
        'name',
        'visibility',
        'created_at',
        'updated_at',
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->orderBy = $request->input('order_by', 'created_at');
        $this->orderDir = $request->input('order_dir', 'desc');

        $this->checkOrdering();

        $tags = Tag::byUser()
            ->orderBy($this->orderBy, $this->orderDir)
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
     * @param Tag $tag
     * @return JsonResponse
     */
    public function show(Tag $tag): JsonResponse
    {
        return response()->json($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TagUpdateRequest $request
     * @param Tag              $tag
     * @return JsonResponse
     */
    public function update(TagUpdateRequest $request, Tag $tag): JsonResponse
    {
        $updatedTag = TagRepository::update($tag, $request->all());

        return response()->json($updatedTag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tag $tag
     * @return JsonResponse
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $deletionSuccessful = TagRepository::delete($tag);

        if ($deletionSuccessful) {
            return response()->json(null, Response::HTTP_OK);
        }

        return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
