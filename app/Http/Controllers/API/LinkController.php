<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\LinkDeleteRequest;
use App\Http\Requests\Models\LinkStoreRequest;
use App\Http\Requests\Models\LinkUpdateRequest;
use App\Models\Link;
use App\Repositories\LinkRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $links = Link::byUser(auth()->id())
            ->orderBy(
                $request->get('order_by', 'created_at'),
                $request->get('order_dir', 'DESC')
            )
            ->paginate(getPaginationLimit());

        return response()->json($links);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LinkStoreRequest $request
     * @return Response
     */
    public function store(LinkStoreRequest $request)
    {
        $link = LinkRepository::create($request->all());

        return response()->json($link);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $link = Link::with(['lists', 'tags'])->findOrFail($id);

        return response()->json($link);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LinkUpdateRequest $request
     * @param int               $id
     * @return Response
     */
    public function update(LinkUpdateRequest $request, $id)
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
     * @return Response
     */
    public function destroy(LinkDeleteRequest $request, $id)
    {
        $link = Link::findOrFail($id);

        $deletionSuccessfull = LinkRepository::delete($link);

        if ($deletionSuccessfull) {
            return response(null, Response::HTTP_OK);
        }

        return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
