<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class TrashController
 *
 * @package App\Http\Controllers\App
 */
class TrashController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $links = Link::onlyTrashed()
            ->byUser(auth()->id())
            ->get();

        $lists = LinkList::onlyTrashed()
            ->byUser(auth()->id())
            ->get();

        $tags = Tag::onlyTrashed()
            ->byUser(auth()->id())
            ->get();

        $notes = Note::onlyTrashed()
            ->byUser(auth()->id())
            ->get();

        return view('actions.trash.index', [
            'links' => $links,
            'lists' => $lists,
            'tags' => $tags,
            'notes' => $notes,
        ]);
    }

    /**
     * Permanently delete entries for a model from the trash
     *
     * @param Request $reques
     * @param string  $model
     * @return RedirectResponse
     */
    public function clearTrash(Request $reques, $model)
    {
        $entries = [];

        switch ($model) {
            case 'links':
                $entries = Link::onlyTrashed()
                    ->byUser(auth()->id())
                    ->get();
                break;
            case 'lists':
                $entries = LinkList::onlyTrashed()
                    ->byUser(auth()->id())
                    ->get();
                break;
            case 'tags':
                $entries = Tag::onlyTrashed()
                    ->byUser(auth()->id())
                    ->get();
                break;
            case 'notes':
                $entries = Note::onlyTrashed()
                    ->byUser(auth()->id())
                    ->get();
                break;
        }

        if ($entries->isEmpty()) {
            alert(trans('trash.delete_no_entries'), 'warning');
            return redirect()->back();
        }

        foreach ($entries as $entry) {
            $entry->forceDelete();
        }

        alert(trans('trash.delete_success.' . $model), 'success');

        return redirect()->route('get-trash');
    }

    /**
     * Restore an entry from the trash
     *
     * @param Request $request
     * @param string  $model
     * @param string  $id
     * @return Factory|View
     */
    public function restoreEntry(Request $request, $model, $id)
    {
        $entry = null;

        switch ($model) {
            case 'link':
                $entry = Link::withTrashed()->find($id);
                break;
            case 'list':
                $entry = LinkList::withTrashed()->find($id);
                break;
            case 'tag':
                $entry = Tag::withTrashed()->find($id);
                break;
            case 'note':
                $entry = Note::withTrashed()->find($id);
                break;
        }

        if (empty($entry)) {
            abort(404, trans('trash.restore.not_found'));
        }

        if ($entry->user_id !== auth()->id()) {
            abort(403, trans('trash.restore.not_allowed'));
        }

        $entry->restore();

        alert(trans('trash.restore.' . $model), 'success');

        return redirect()->route('get-trash');
    }
}
