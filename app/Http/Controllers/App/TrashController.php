<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
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
     * @param         $model
     * @return \Illuminate\Http\Response
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

        if (empty($entries)) {
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
     * @param         $model
     * @param         $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
            abort(404);
        }

        if ($entry->user_id !== auth()->id()) {
            abort(403);
        }

        $entry->restore();

        alert(trans('trash.delete_restore.' . $model), 'success');

        return redirect()->route('get-trash');
    }
}
