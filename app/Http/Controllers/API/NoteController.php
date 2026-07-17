<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\NoteStoreRequest;
use App\Http\Requests\Models\NoteUpdateRequest;
use App\Models\Api\ApiNote;
use App\Repositories\NoteRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ApiNote::class, 'note');
    }

    public function store(NoteStoreRequest $request): JsonResponse
    {
        $note = NoteRepository::create($request->validated());

        return response()->json($note);
    }

    public function update(NoteUpdateRequest $request, ApiNote $note): JsonResponse
    {
        $this->authorize('update', $note);

        $updatedNote = NoteRepository::update($note, $request->validated());

        return response()->json($updatedNote);
    }

    public function destroy(ApiNote $note): JsonResponse
    {
        $deletionSuccessful = NoteRepository::delete($note);

        if ($deletionSuccessful) {
            return response()->json();
        }

        return response()->json(status: Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
