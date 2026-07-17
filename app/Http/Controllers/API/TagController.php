<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Requests\Models\TagStoreRequest;
use App\Http\Requests\Models\TagUpdateRequest;
use App\Models\Api\ApiTag;
use App\Repositories\TagRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ChecksOrdering;

    public function __construct()
    {
        $this->allowedOrderBy = ApiTag::$allowOrderBy;
        $this->authorizeResource(ApiTag::class, 'tag');
    }

    public function index(Request $request): JsonResponse
    {
        $this->orderBy = $request->input('order_by', 'created_at');
        $this->orderDir = $request->input('order_dir', 'desc');

        $this->checkOrdering();

        $tags = ApiTag::query()
            ->visibleForUser()
            ->orderBy($this->orderBy, $this->orderDir)
            ->paginate(getPaginationLimit());

        return response()->json($tags);
    }

    public function store(TagStoreRequest $request): JsonResponse
    {
        $tag = TagRepository::create($request->validated());

        return response()->json($tag);
    }

    public function show(ApiTag $tag): JsonResponse
    {
        return response()->json($tag);
    }

    public function update(TagUpdateRequest $request, ApiTag $tag): JsonResponse
    {
        $this->authorize('update', $tag);

        $updatedTag = TagRepository::update($tag, $request->validated());

        return response()->json($updatedTag);
    }

    public function destroy(ApiTag $tag): JsonResponse
    {
        $deletionSuccessful = TagRepository::delete($tag);

        if ($deletionSuccessful) {
            return response()->json();
        }

        return response()->json(status: 500);
    }
}
