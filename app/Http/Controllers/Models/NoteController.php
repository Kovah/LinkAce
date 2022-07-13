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

    /**
     * Store a newly created resource in storage.
     *
     * @param NoteStoreRequest $request
     * @return RedirectResponse
     */
    public function store(NoteStoreRequest $request): RedirectResponse
    {
        $link = Link::findOrFail($request->input('link_id'));

        $data = $request->validated();
        NoteRepository::create($data);

        flash(trans('note.added_successfully'), 'success');

        return redirect()->route('links.show', ['link' => $link]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Note $note
     * @return View
     */
    public function edit(Note $note): View
    {
        return view('models.notes.edit', ['note' => $note]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NoteUpdateRequest $request
     * @param Note              $note
     * @return RedirectResponse
     */
    public function update(NoteUpdateRequest $request, Note $note): RedirectResponse
    {
        $note = NoteRepository::update($note, $request->except(['_token']));

        flash(trans('note.updated_successfully'), 'success');

        return redirect()->route('links.show', ['link' => $note->link]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Note $note
     * @return RedirectResponse
     * @throws Exception
     */
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
