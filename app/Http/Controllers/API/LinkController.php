<?php

namespace App\Http\Controllers\API;

use App\Enums\ApiToken;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Requests\Models\LinkStoreRequest;
use App\Http\Requests\Models\LinkUpdateRequest;
use App\Models\Link;
use App\Repositories\LinkRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    use ChecksOrdering;

    public function __construct()
    {
        $this->allowedOrderBy = Link::$allowOrderBy;
        $this->authorizeResource(Link::class . 'Api', 'link');
    }

    public function index(Request $request): JsonResponse
    {
        $this->orderBy = $request->input('order_by', 'created_at');
        $this->orderDir = $request->input('order_dir', 'desc');

        $this->checkOrdering();

        $links = Link::query()
            ->visibleForUser(privateSystemAccess: $request->user()->tokenCan(ApiToken::ABILITY_SYSTEM_ACCESS_PRIVATE))
            ->orderBy($this->orderBy, $this->orderDir)
            ->paginate(getPaginationLimit());

        return response()->json($links);
    }

    public function store(LinkStoreRequest $request): JsonResponse
    {
        $link = LinkRepository::create($request->all());

        return response()->json($link);
    }

    public function show(Link $link): JsonResponse
    {
        $link->load(['lists', 'tags']);

        return response()->json($link);
    }

    public function update(LinkUpdateRequest $request, Link $link): JsonResponse
    {
        $updatedLink = LinkRepository::update($link, $request->all());

        return response()->json($updatedLink);
    }

    public function destroy(Link $link): JsonResponse
    {
        $deletionSuccessful = LinkRepository::delete($link);

        if ($deletionSuccessful) {
            return response()->json();
        }

        return response()->json(status: 500);
    }
}
