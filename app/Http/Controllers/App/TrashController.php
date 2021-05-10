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
    /**
     * Display the trash overview with all deleted entries for links, tags, etc.
     *
     * @return View
     */
    public function index(): View
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

        return view('app.trash.index', [
            'links' => $links,
            'lists' => $lists,
            'tags' => $tags,
            'notes' => $notes,
        ]);
    }

    /**
     * Permanently delete entries for a model from the trash.
     *
     * @param TrashClearRequest $request
     * @return RedirectResponse
     */
    public function clearTrash(TrashClearRequest $request): RedirectResponse
    {
        TrashRepository::delete($request->input('model'));

        flash(trans('trash.delete_success.' . $request->input('model')), 'success');
        return redirect()->route('get-trash');
    }

    /**
     * Restore an entry from the trash.
     *
     * @param TrashRestoreRequest $request
     * @return RedirectResponse
     */
    public function restoreEntry(TrashRestoreRequest $request): RedirectResponse
    {
        TrashRepository::restore($request->input('model'), $request->input('id'));

        flash(trans('trash.restore.' . $request->input('model')), 'success');
        return redirect()->route('get-trash');
    }
}
