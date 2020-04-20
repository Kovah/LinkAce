<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\NoteDeleteRequest;
use App\Http\Requests\Models\NoteStoreRequest;
use App\Http\Requests\Models\NoteUpdateRequest;
use App\Models\Note;
use App\Repositories\NoteRepository;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param NoteStoreRequest $request
     * @return Response
     */
    public function store(NoteStoreRequest $request)
    {
        $note = NoteRepository::create($request->all());

        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NoteUpdateRequest $request
     * @param int               $id
     * @return Response
     */
    public function update(NoteUpdateRequest $request, $id)
    {
        $note = Note::findOrFail($id);

        $updatedNote = NoteRepository::update($note, $request->all());

        return response()->json($updatedNote);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param NoteDeleteRequest $request
     * @param int               $id
     * @return Response
     */
    public function destroy(NoteDeleteRequest $request, $id)
    {
        $note = Note::findOrFail($id);

        $deletionSuccessfull = NoteRepository::delete($note);

        if ($deletionSuccessfull) {
            return response(null, Response::HTTP_OK);
        }

        return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
