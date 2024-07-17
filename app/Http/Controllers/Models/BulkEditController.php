<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\BulkDeleteRequest;
use App\Http\Requests\Models\BulkEditFormRequest;
use App\Http\Requests\Models\BulkEditLinksRequest;
use App\Http\Requests\Models\BulkEditListsRequest;
use App\Http\Requests\Models\BulkEditTagsRequest;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Repositories\LinkRepository;
use App\Repositories\ListRepository;
use App\Repositories\TagRepository;
use Illuminate\Support\Facades\Log;

class BulkEditController extends Controller
{
    public function form(BulkEditFormRequest $request)
    {
        $type = $request->input('type');
        $view = sprintf('models.%s.bulk-edit', $type);

        $models = explode(',', $request->input('models'));

        return view($view, [
            'models' => $models,
            'modelCount' => count($models),
        ]);
    }

    public function updateLinks(BulkEditLinksRequest $request)
    {
        $models = explode(',', $request->input('models'));

        $results = LinkRepository::bulkUpdate($models, $request->input());

        $successCount = $results->filter(fn($e) => $e !== null)->count();

        flash(trans('link.bulk_edit_success', ['success' => $successCount, 'selected' => count($models)]));
        return redirect()->route('links.index');
    }

    public function updateLists(BulkEditListsRequest $request)
    {
        $models = explode(',', $request->input('models'));

        $results = ListRepository::bulkUpdate($models, $request->input());

        $successCount = $results->filter(fn($e) => $e !== null)->count();

        flash(trans('list.bulk_edit_success', ['success' => $successCount, 'selected' => count($models)]));
        return redirect()->route('lists.index');
    }

    public function updateTags(BulkEditTagsRequest $request)
    {
        $models = explode(',', $request->input('models'));

        $results = TagRepository::bulkUpdate($models, $request->input());

        $successCount = $results->filter(fn($e) => $e !== null)->count();

        flash(trans('tag.bulk_edit_success', ['success' => $successCount, 'selected' => count($models)]));
        return redirect()->route('tags.index');
    }

    public function delete(BulkDeleteRequest $request)
    {
        $type = $request->input('type');
        $formModels = explode(',', $request->input('models'));
        $models = match ($type) {
            'links' => Link::whereIn('id', $formModels)->get(),
            'lists' => LinkList::whereIn('id', $formModels)->get(),
            'tags' => Tag::whereIn('id', $formModels)->get(),
        };

        $results = $models->map(function ($model) use ($type) {
            if (!auth()->user()->can('delete', $model)) {
                Log::warning('Could not delete ' . $type . ' ' . $model->id . ' during bulk deletion: Permission denied!');
                return null;
            }
            return match ($type) {
                'links' => LinkRepository::delete($model),
                'lists' => ListRepository::delete($model),
                'tags' => TagRepository::delete($model)
            };
        });

        $successCount = $results->filter(fn($e) => $e !== null)->count();

        $message = match ($type) {
            'links' => 'link.bulk_delete_success',
            'lists' => 'list.bulk_delete_success',
            'tags' => 'tag.bulk_delete_success'
        };
        flash(trans($message, ['success' => $successCount, 'selected' => $models->count()]));
        return redirect()->route($type . '.index');
    }
}
