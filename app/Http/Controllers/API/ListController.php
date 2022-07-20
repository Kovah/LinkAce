<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Requests\Models\ListStoreRequest;
use App\Http\Requests\Models\ListUpdateRequest;
use App\Models\LinkList;
use App\Repositories\ListRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListController extends Controller
{
    use ChecksOrdering;

    public function __construct()
    {
        $this->allowedOrderBy = LinkList::$allowOrderBy;
        $this->authorizeResource(LinkList::class, 'list');
    }

    public function index(Request $request): JsonResponse
    {
        $this->orderBy = $request->input('order_by', 'created_at');
        $this->orderDir = $request->input('order_dir', 'desc');

        $this->checkOrdering();

        $lists = LinkList::query()
            ->visibleForUser()
            ->orderBy($this->orderBy, $this->orderDir)
            ->paginate(getPaginationLimit());

        return response()->json($lists);
    }

    public function store(ListStoreRequest $request): JsonResponse
    {
        $link = ListRepository::create($request->all());

        return response()->json($link);
    }

    public function show(LinkList $list): JsonResponse
    {
        // Instead of displaying all links for that list, show the URL to directly fetch all links for that list
        $list->setAttribute('links', route('api.lists.links', ['list' => $list], true));

        return response()->json($list);
    }

    public function update(ListUpdateRequest $request, LinkList $list): JsonResponse
    {
        $updatedList = ListRepository::update($list, $request->all());

        return response()->json($updatedList);
    }

    public function destroy(LinkList $list): JsonResponse
    {
        $deletionSuccessful = ListRepository::delete($list);

        if ($deletionSuccessful) {
            return response()->json();
        }

        return response()->json(status: 500);
    }
}
