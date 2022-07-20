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
    public function __construct()
    {
        $this->authorizeResource(Note::class, 'note');
    }

    public function store(NoteStoreRequest $request): JsonResponse
    {
        $note = NoteRepository::create($request->all());

        return response()->json($note);
    }

    public function update(NoteUpdateRequest $request, Note $note): JsonResponse
    {
        $updatedNote = NoteRepository::update($note, $request->validated());

        return response()->json($updatedNote);
    }

    public function destroy(Note $note): JsonResponse
    {
        $deletionSuccessful = NoteRepository::delete($note);

        if ($deletionSuccessful) {
            return response()->json();
        }

        return response()->json(status: Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
