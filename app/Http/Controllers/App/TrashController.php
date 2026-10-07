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
            'pageTitle' => trans('trash.trash'),
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
        $model = TrashRepository::restore($request->input('model'), $request->input('id'));

        flash(trans('trash.restore.' . $request->input('model')), 'success');

        if ($request->boolean('redirect_to_model')) {
            return match ($request->input('model')) {
                'link' => redirect()->route('links.show', ['link' => $model]),
                'list' => redirect()->route('lists.show', ['list' => $model]),
                'tag' => redirect()->route('tags.show', ['tag' => $model]),
                'note' => $this->redirectToNoteLink($model),
                default => redirect()->route('get-trash'),
            };
        }

        return redirect()->route('get-trash');
    }

    /**
     * Redirect to the link a restored note belongs to. The link itself may still be
     * in the trash, in which case there is nothing to redirect to.
     *
     * @param Note $note
     * @return RedirectResponse
     */
    protected function redirectToNoteLink(Note $note): RedirectResponse
    {
        $link = $note->link()->first();

        if ($link === null) {
            return redirect()->route('get-trash');
        }

        return redirect()->route('links.show', ['link' => $link]);
    }
}
