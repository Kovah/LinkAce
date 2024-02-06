<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\NoteStoreRequest;
use App\Http\Requests\Models\NoteUpdateRequest;
use App\Models\Link;
use App\Models\Note;
use App\Repositories\NoteRepository;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Note::class, 'note');
    }

    public function store(NoteStoreRequest $request): RedirectResponse
    {
        $link = Link::findOrFail($request->input('link_id'));

        $data = $request->validated();
        NoteRepository::create($data);

        flash(trans('note.added_successfully'), 'success');

        return redirect()->route('links.show', ['link' => $link]);
    }

    public function edit(Note $note): View
    {
        return view('models.notes.edit', [
            'pageTitle' => trans('note.edit'),
            'note' => $note,
        ]);
    }

    public function update(NoteUpdateRequest $request, Note $note): RedirectResponse
    {
        $note = NoteRepository::update($note, $request->except(['_token']));

        flash(trans('note.updated_successfully'), 'success');

        return redirect()->route('links.show', ['link' => $note->link]);
    }

    public function destroy(Note $note): RedirectResponse
    {
        $link = $note->link;

        $deletionSuccessful = NoteRepository::delete($note);

        if (!$deletionSuccessful) {
            flash(trans('note.deletion_error'), 'error');
            return redirect()->back();
        }

        flash(trans('note.deleted_successfully'), 'warning');
        return redirect()->route('links.show', ['link' => $link]);
    }
}
