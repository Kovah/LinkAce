<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteDeleteRequest;
use App\Http\Requests\NoteStoreRequest;
use App\Http\Requests\NoteUpdateRequest;
use App\Models\Link;
use App\Models\Note;
use App\Repositories\NoteRepository;

class NoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param NoteStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NoteStoreRequest $request)
    {
        $link = Link::find($request->get('link_id'));

        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->except(['_token']);
        $note = NoteRepository::create($data);

        alert(trans('note.added_successfully'), 'success');

        return redirect()->route('links.show', [$link->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $note = Note::find($id);

        if (empty($note)) {
            abort(404);
        }

        if ($note->user_id !== auth()->id()) {
            abort(404);
        }

        return view('models.notes.edit')->with('note', $note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NoteUpdateRequest $request
     * @param int               $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(NoteUpdateRequest $request, $id)
    {
        $note = Note::find($request->get('note_id'));

        if (empty($note)) {
            abort(404);
        }

        $data = $request->except(['_token', 'note_id']);
        $note = NoteRepository::update($note, $data);

        alert(trans('note.updated_successfully'), 'success');

        return redirect()->route('links.show', [$note->link->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param NoteDeleteRequest $request
     * @param int               $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(NoteDeleteRequest $request, $id)
    {
        $note = Note::find($id);

        if (empty($note)) {
            abort(404);
        }

        if ($note->link->user_id !== auth()->id()) {
            abort(404);
        }

        $link = $note->link->id;

        $deletion_successfull = NoteRepository::delete($note);

        if (!$deletion_successfull) {
            alert(trans('note.deletion_error'), 'error');
            return redirect()->back();
        }

        alert(trans('note.deleted_successfully'), 'warning');

        return redirect()->route('links.show', [$link]);
    }
}
