<?php

namespace App\Http\Controllers\API;

use App\Enums\ApiToken;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Requests\Models\LinkStoreRequest;
use App\Http\Requests\Models\LinkUpdateRequest;
use App\Models\Api\ApiLink;
use App\Repositories\LinkRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    use ChecksOrdering;

    public function __construct()
    {
        $this->allowedOrderBy = ApiLink::$allowOrderBy;
        $this->authorizeResource(ApiLink::class, 'link');
    }

    public function index(Request $request): JsonResponse
    {
        $this->orderBy = $request->input('order_by', 'created_at');
        $this->orderDir = $request->input('order_dir', 'desc');

        $this->checkOrdering();

        $links = ApiLink::query()
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

    public function show(Request $request, ApiLink $link): JsonResponse
    {
        $link->load([
            'lists' => function ($query) use ($request) {
                $query->visibleForUser(privateSystemAccess: $request->user()->tokenCan(ApiToken::ABILITY_SYSTEM_ACCESS_PRIVATE));
            },
            'tags' => function ($query) use ($request) {
                $query->visibleForUser(privateSystemAccess: $request->user()->tokenCan(ApiToken::ABILITY_SYSTEM_ACCESS_PRIVATE));
            },
        ]);

        return response()->json($link);
    }

    public function update(LinkUpdateRequest $request, ApiLink $link): JsonResponse
    {
        $updatedLink = LinkRepository::update($link, $request->all());

        return response()->json($updatedLink);
    }

    public function destroy(ApiLink $link): JsonResponse
    {
        $deletionSuccessful = LinkRepository::delete($link);

        if ($deletionSuccessful) {
            return response()->json();
        }

        return response()->json(status: 500);
    }
}
