<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Requests\Models\LinkStoreRequest;
use App\Http\Requests\Models\LinkUpdateRequest;
use App\Models\Link;
use App\Repositories\LinkRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LinkController extends Controller
{
    use ChecksOrdering;

    protected array $allowedOrders = [
        'url',
        'title',
        'description',
        'visibility',
        'status',
        'check_disabled',
        'created_at',
        'updated_at',
    ];

    public function __construct()
    {
        $this->allowedOrders = Link::$allowOrderBy;
        $this->authorizeResource(Link::class, 'link');
    }

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

        $links = Link::query()
            ->visibleForUser()
            ->orderBy($this->orderBy, $this->orderDir)
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
     * @param Link $link
     * @return JsonResponse
     */
    public function show(Link $link): JsonResponse
    {
        $link->load(['lists', 'tags']);

        return response()->json($link);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LinkUpdateRequest $request
     * @param Link              $link
     * @return JsonResponse
     */
    public function update(LinkUpdateRequest $request, Link $link): JsonResponse
    {
        $updatedLink = LinkRepository::update($link, $request->all());

        return response()->json($updatedLink);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Link $link
     * @return JsonResponse
     */
    public function destroy(Link $link): JsonResponse
    {
        $deletionSuccessful = LinkRepository::delete($link);

        if ($deletionSuccessful) {
            return response()->json(null, Response::HTTP_OK);
        }

        return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
