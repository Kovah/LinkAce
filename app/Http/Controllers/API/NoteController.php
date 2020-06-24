<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\NoteDeleteRequest;
use App\Http\Requests\Models\NoteStoreRequest;
use App\Http\Requests\Models\NoteUpdateRequest;
use App\Models\Note;
use App\Repositories\NoteRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param NoteStoreRequest $request
     * @return JsonResponse
     */
    public function store(NoteStoreRequest $request): JsonResponse
    {
        $note = NoteRepository::create($request->all());

        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NoteUpdateRequest $request
     * @param int               $id
     * @return JsonResponse
     */
    public function update(NoteUpdateRequest $request, $id): JsonResponse
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
     * @return JsonResponse
     */
    public function destroy(NoteDeleteRequest $request, $id): JsonResponse
    {
        $note = Note::findOrFail($id);

        $deletionSuccessfull = NoteRepository::delete($note);

        if ($deletionSuccessfull) {
            return response()->json(null, Response::HTTP_OK);
        }

        return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
