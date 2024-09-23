<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\Api\BulkEditListsRequest;
use App\Http\Requests\Models\Api\BulkEditTagsRequest;
use App\Http\Requests\Models\Api\BulkStoreLinksRequest;
use App\Http\Requests\Models\Api\BulkStoreListsRequest;
use App\Http\Requests\Models\Api\BulkStoreTagsRequest;
use App\Repositories\LinkRepository;
use App\Repositories\ListRepository;
use App\Repositories\TagRepository;
use Illuminate\Http\JsonResponse;

class BulkStoreController extends Controller
{
    public function storeLinks(BulkStoreLinksRequest $request): JsonResponse
    {
        $models = $request->input('models');

        $results = [];
        foreach ($models as $model) {
            $results[] = LinkRepository::create($model);
        }

        return response()->json($results);
    }

    public function storeLists(BulkStoreListsRequest $request): JsonResponse
    {
        $models = $request->input('models');

        $results = [];
        foreach ($models as $model) {
            $results[] = ListRepository::create($model);
        }

        return response()->json($results);
    }

    public function storeTags(BulkStoreTagsRequest $request): JsonResponse
    {
        $models = $request->input('models');

        $results = [];
        foreach ($models as $model) {
            $results[] = TagRepository::create($model);
        }

        return response()->json($results);
    }
}
