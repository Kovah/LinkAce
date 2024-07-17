<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\Api\BulkDeleteRequest;
use App\Http\Requests\Models\Api\BulkEditLinksRequest;
use App\Http\Requests\Models\Api\BulkEditListsRequest;
use App\Http\Requests\Models\Api\BulkEditTagsRequest;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Repositories\LinkRepository;
use App\Repositories\ListRepository;
use App\Repositories\TagRepository;
use Illuminate\Support\Facades\Log;

class BulkEditController extends Controller
{
    public function updateLinks(BulkEditLinksRequest $request)
    {
        $models = $request->input('models');

        $results = LinkRepository::bulkUpdate($models, $request->input());

        return response()->json($results);
    }

    public function updateLists(BulkEditListsRequest $request)
    {
        $models = $request->input('models');

        $results = ListRepository::bulkUpdate($models, $request->input());

        return response()->json($results);
    }

    public function updateTags(BulkEditTagsRequest $request)
    {
        $models = $request->input('models');

        $results = TagRepository::bulkUpdate($models, $request->input());

        return response()->json($results);
    }

    public function delete(BulkDeleteRequest $request)
    {
        $type = $request->input('type');
        $formModels = $request->input('models');
        $models = match ($type) {
            'links' => Link::whereIn('id', $formModels)->get(),
            'lists' => LinkList::whereIn('id', $formModels)->get(),
            'tags' => Tag::whereIn('id', $formModels)->get(),
        };

        $results = $models->mapWithKeys(function ($model) use ($type) {
            if (!auth()->user()->can('delete', $model)) {
                Log::warning('Could not delete ' . $type . ' ' . $model->id . ' during bulk deletion: Permission denied!');
                return [$model->id => false];
            }
            return match ($type) {
                'links' => [$model->id => LinkRepository::delete($model) === true],
                'lists' => [$model->id => ListRepository::delete($model) === true],
                'tags' => [$model->id => TagRepository::delete($model) === true],
            };
        });

        return response()->json($results);
    }
}
