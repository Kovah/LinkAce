<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\ListDeleteRequest;
use App\Http\Requests\Models\ListStoreRequest;
use App\Http\Requests\Models\ListUpdateRequest;
use App\Models\LinkList;
use App\Repositories\ListRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $lists = LinkList::byUser(auth()->id())
            ->orderBy(
                $request->input('order_by', 'created_at'),
                $request->input('order_dir', 'DESC')
            )
            ->paginate(getPaginationLimit());

        return response()->json($lists);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ListStoreRequest $request
     * @return JsonResponse
     */
    public function store(ListStoreRequest $request): JsonResponse
    {
        $link = ListRepository::create($request->all());

        return response()->json($link);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $list = LinkList::findOrFail($id);

        $list->links = route('api.lists.links', [$id], true);

        return response()->json($list);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ListUpdateRequest $request
     * @param int               $id
     * @return JsonResponse
     */
    public function update(ListUpdateRequest $request, $id): JsonResponse
    {
        $list = LinkList::findOrFail($id);

        $updatedList = ListRepository::update($list, $request->all());

        return response()->json($updatedList);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ListDeleteRequest $request
     * @param int               $id
     * @return JsonResponse
     */
    public function destroy(ListDeleteRequest $request, $id): JsonResponse
    {
        $list = LinkList::findOrFail($id);

        $deletionSuccessfull = ListRepository::delete($list);

        if ($deletionSuccessfull) {
            return response()->json(null, Response::HTTP_OK);
        }

        return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
