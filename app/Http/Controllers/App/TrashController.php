<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrashClearRequest;
use App\Http\Requests\TrashRestoreRequest;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use App\Repositories\TrashRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TrashController extends Controller
{
    public function index(): View
    {
        $links = Link::onlyTrashed()->byUser()->get();
        $lists = LinkList::onlyTrashed()->byUser()->get();
        $tags = Tag::onlyTrashed()->byUser()->get();
        $notes = Note::onlyTrashed()->byUser()->get();

        return view('app.trash.index', [
            'links' => $links,
            'lists' => $lists,
            'tags' => $tags,
            'notes' => $notes,
        ]);
    }

    public function clearTrash(TrashClearRequest $request): RedirectResponse
    {
        TrashRepository::delete($request->input('model'));

        flash(trans('trash.delete_success.' . $request->input('model')), 'success');
        return redirect()->route('get-trash');
    }

    public function restoreEntry(TrashRestoreRequest $request): RedirectResponse
    {
        TrashRepository::restore($request->input('model'), $request->input('id'));

        flash(trans('trash.restore.' . $request->input('model')), 'success');
        return redirect()->route('get-trash');
    }
}
