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
        $links = Link::whereIn('id', $models)->with([
            'tags:id',
            'lists:id',
        ])->get();

        $results = $links->map(function (Link $link) use ($request) {
            if (!auth()->user()->can('update', $link)) {
                Log::warning('Could not update ' . $link->id . ' during bulk update: Permission denied!');
                return null;
            }

            $newTags = explode(',', $request->input('tags'));
            $newLists = explode(',', $request->input('lists'));

            $linkData = $link->toArray();
            $linkData['tags'] = $request->input('tags_mode') === 'replace'
                ? $newTags
                : array_merge($link->tags->pluck('id')->toArray(), $newTags);
            $linkData['lists'] = $request->input('lists_mode') === 'replace'
                ? $newLists
                : array_merge($link->lists->pluck('id')->toArray(), $newLists);
            $linkData['visibility'] = $request->input('visibility') ?: $linkData['visibility'];

            return LinkRepository::update($link, $linkData);
        });

        $successCount = $results->filter(fn($e) => $e !== null)->count();

        flash(trans('link.bulk_edit_success', ['success' => $successCount, 'selected' => $links->count()]));
        return redirect()->route('links.index');
    }

    public function updateLists(BulkEditListsRequest $request)
    {
        $models = explode(',', $request->input('models'));
        $lists = LinkList::whereIn('id', $models)->get();

        $results = $lists->map(function (LinkList $list) use ($request) {
            if (!auth()->user()->can('update', $list)) {
                Log::warning('Could not update list ' . $list->id . ' during bulk update: Permission denied!');
                return null;
            }

            $listData = $list->toArray();
            $listData['visibility'] = $request->input('visibility') ?: $listData['visibility'];

            return ListRepository::update($list, $listData);
        });

        $successCount = $results->filter(fn($e) => $e !== null)->count();

        flash(trans('list.bulk_edit_success', ['success' => $successCount, 'selected' => $lists->count()]));
        return redirect()->route('lists.index');
    }

    public function updateTags(BulkEditTagsRequest $request)
    {
        $models = explode(',', $request->input('models'));
        $tags = Tag::whereIn('id', $models)->get();

        $results = $tags->map(function (Tag $tag) use ($request) {
            if (!auth()->user()->can('update', $tag)) {
                Log::warning('Could not update tag ' . $tag->id . ' during bulk update: Permission denied!');
                return null;
            }

            $tagData = $tag->toArray();
            $tagData['visibility'] = $request->input('visibility') ?: $tagData['visibility'];

            return TagRepository::update($tag, $tagData);
        });

        $successCount = $results->filter(fn($e) => $e !== null)->count();

        flash(trans('tag.bulk_edit_success', ['success' => $successCount, 'selected' => $tags->count()]));
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
