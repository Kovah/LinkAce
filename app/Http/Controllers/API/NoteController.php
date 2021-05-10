<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
     * @param Note              $note
     * @return JsonResponse
     */
    public function update(NoteUpdateRequest $request, Note $note): JsonResponse
    {
        $updatedNote = NoteRepository::update($note, $request->all());

        return response()->json($updatedNote);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Note $note
     * @return JsonResponse
     */
    public function destroy(Note $note): JsonResponse
    {
        $deletionSuccessful = NoteRepository::delete($note);

        if ($deletionSuccessful) {
            return response()->json(null, Response::HTTP_OK);
        }

        return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
