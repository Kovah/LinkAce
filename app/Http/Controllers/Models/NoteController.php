<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\NoteDeleteRequest;
use App\Http\Requests\Models\NoteStoreRequest;
use App\Http\Requests\Models\NoteUpdateRequest;
use App\Models\Link;
use App\Models\Note;
use App\Repositories\NoteRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param NoteStoreRequest $request
     * @return RedirectResponse
     */
    public function store(NoteStoreRequest $request): RedirectResponse
    {
        $link = Link::findOrFail($request->input('link_id'));

        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->except(['_token']);
        NoteRepository::create($data);

        flash(trans('note.added_successfully'), 'success');

        return redirect()->route('links.show', [$link->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function edit($id): View
    {
        $note = Note::findOrFail($id);

        if ($note->user_id !== auth()->id()) {
            abort(403);
        }

        return view('models.notes.edit')->with('note', $note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NoteUpdateRequest $request
     * @param int               $id
     * @return RedirectResponse
     */
    public function update(NoteUpdateRequest $request, $id): RedirectResponse
    {
        $note = Note::findOrFail($id);

        $note = NoteRepository::update($note, $request->except(['_token']));

        flash(trans('note.updated_successfully'), 'success');

        return redirect()->route('links.show', [$note->link->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param NoteDeleteRequest $request
     * @param int               $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(NoteDeleteRequest $request, $id): RedirectResponse
    {
        $note = Note::findOrFail($id);

        $linkId = $note->link->id;

        $deletionSuccessfull = NoteRepository::delete($note);

        if (!$deletionSuccessfull) {
            flash(trans('note.deletion_error'), 'error');
            return redirect()->back();
        }

        flash(trans('note.deleted_successfully'), 'warning');

        return redirect()->route('links.show', [$linkId]);
    }
}
