<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Requests\Models\TagStoreRequest;
use App\Http\Requests\Models\TagUpdateRequest;
use App\Models\Api\ApiTag;
use App\Models\Tag;
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

        $tags = Tag::query()
            ->visibleForUser()
            ->orderBy($this->orderBy, $this->orderDir)
            ->paginate(getPaginationLimit());

        return response()->json($tags);
    }

    public function store(TagStoreRequest $request): JsonResponse
    {
        $tag = TagRepository::create($request->all());

        return response()->json($tag);
    }

    public function show(Tag $tag): JsonResponse
    {
        return response()->json($tag);
    }

    public function update(TagUpdateRequest $request, Tag $tag): JsonResponse
    {
        $updatedTag = TagRepository::update($tag, $request->all());

        return response()->json($updatedTag);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $deletionSuccessful = TagRepository::delete($tag);

        if ($deletionSuccessful) {
            return response()->json();
        }

        return response()->json(status: 500);
    }
}
