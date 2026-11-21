<?php

namespace App\Http\Controllers\API;

use App\Enums\ApiToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrashClearRequest;
use App\Http\Requests\TrashRestoreRequest;
use App\Models\Api\ApiLink;
use App\Models\Api\ApiLinkList;
use App\Models\Api\ApiNote;
use App\Models\Api\ApiTag;
use App\Repositories\TrashRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function getLinks(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ApiLink::class);

        $links = ApiLink::onlyTrashed()
            ->byUser($request->user()->id)
            ->visibleForUser($request->user()->id, $this->canAccessPrivateEntries($request))
            ->get();

        return response()->json($links->toArray());
    }

    public function getLists(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ApiLinkList::class);

        $lists = ApiLinkList::onlyTrashed()
            ->byUser($request->user()->id)
            ->visibleForUser($request->user()->id, $this->canAccessPrivateEntries($request))
            ->get();

        return response()->json($lists);
    }

    public function getTags(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ApiTag::class);

        $tags = ApiTag::onlyTrashed()
            ->byUser($request->user()->id)
            ->visibleForUser($request->user()->id, $this->canAccessPrivateEntries($request))
            ->get();

        return response()->json($tags);
    }

    public function getNotes(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ApiNote::class);

        $notes = ApiNote::onlyTrashed()
            ->byUser($request->user()->id)
            ->visibleForUser($request->user()->id, $this->canAccessPrivateEntries($request))
            ->get();

        return response()->json($notes);
    }

    public function clear(TrashClearRequest $request): JsonResponse
    {
        $model = match ($request->input('model')) {
            'links' => ApiLink::class,
            'lists' => ApiLinkList::class,
            'tags' => ApiTag::class,
            'notes' => ApiNote::class,
        };

        // Check all entries first, so the trash is either cleared completely or not at all
        $model::onlyTrashed()
            ->byUser($request->user()->id)
            ->get()
            ->each(fn ($entry) => $this->authorize('forceDelete', $entry));

        TrashRepository::delete($request->input('model'));

        return response()->json();
    }

    /**
     * Restore an entry from the trash.
     *
     * @param TrashRestoreRequest $request
     * @return JsonResponse
     */
    public function restore(TrashRestoreRequest $request): JsonResponse
    {
        $model = match ($request->input('model')) {
            'link' => ApiLink::class,
            'list' => ApiLinkList::class,
            'tag' => ApiTag::class,
            'note' => ApiNote::class,
        };

        $entry = $model::withTrashed()
            ->byUser($request->user()->id)
            ->findOrFail($request->input('id'));
        $this->authorize('restore', $entry);

        TrashRepository::restore($request->input('model'), $request->input('id'));

        return response()->json();
    }

    protected function canAccessPrivateEntries(Request $request): bool
    {
        return !$request->user()->isSystemUser()
            || $request->user()->tokenCan(ApiToken::ABILITY_SYSTEM_ACCESS_PRIVATE);
    }
}
