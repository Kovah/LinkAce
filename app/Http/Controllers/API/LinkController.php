<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\LinkDeleteRequest;
use App\Http\Requests\Models\LinkStoreRequest;
use App\Http\Requests\Models\LinkUpdateRequest;
use App\Models\Link;
use App\Repositories\LinkRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $links = Link::byUser(auth()->id())
            ->orderBy(
                $request->input('order_by', 'created_at'),
                $request->input('order_dir', 'DESC')
            )
            ->paginate(getPaginationLimit());

        return response()->json($links);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LinkStoreRequest $request
     * @return JsonResponse
     */
    public function store(LinkStoreRequest $request): JsonResponse
    {
        $link = LinkRepository::create($request->all());

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
        $link = Link::with(['lists', 'tags'])->findOrFail($id);

        return response()->json($link);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LinkUpdateRequest $request
     * @param int               $id
     * @return JsonResponse
     */
    public function update(LinkUpdateRequest $request, $id): JsonResponse
    {
        $link = Link::findOrFail($id);

        $updatedLink = LinkRepository::update($link, $request->all());

        return response()->json($updatedLink);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param LinkDeleteRequest $request
     * @param int               $id
     * @return JsonResponse
     */
    public function destroy(LinkDeleteRequest $request, $id): JsonResponse
    {
        $link = Link::findOrFail($id);

        $deletionSuccessfull = LinkRepository::delete($link);

        if ($deletionSuccessfull) {
            return response()->json(null, Response::HTTP_OK);
        }

        return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
