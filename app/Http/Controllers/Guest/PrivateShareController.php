<?php

namespace App\Http\Controllers\Guest;

use App\Enums\ModelAttribute;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Controllers\Traits\ConfiguresLinkDisplay;
use App\Models\LinkList;
use App\Models\PrivateShare;
use App\Models\Tag;
use Illuminate\Http\Request;

class PrivateShareController extends Controller
{
    use ChecksOrdering;
    use ConfiguresLinkDisplay;

    public function __invoke(Request $request, PrivateShare $share)
    {
        if (!$request->hasValidSignature() || now()->gte($share->expires_at)) {
            // @TODO gracefully fail
            abort(401);
        }

        if ($share->entity_type === LinkList::class) {
            return $this->displayList($request, $share);
        }

        if ($share->entity_type === Tag::class) {
            return $this->displayTag($request, $share);
        }

        return $this->displayLink($share);
    }

    private function displayList(Request $request, PrivateShare $share)
    {
        $this->updateLinkDisplayForGuest();

        $this->orderBy = $request->input('orderBy', 'created_at');
        $this->orderDir = $request->input('orderBy', 'desc');
        $this->checkOrdering();

        $links = $share->entity->links()
            ->publicOnly()
            ->orderBy($this->orderBy, $this->orderDir)
            ->paginate(getPaginationLimit());

        return view('guest.lists.show', [
            'pageTitle' => trans('list.list') . ': ' . $share->entity->name,
            'list' => $share->entity,
            'links' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
        ]);
    }

    private function displayTag(Request $request, PrivateShare $share)
    {
        $this->updateLinkDisplayForGuest();

        $this->orderBy = $request->input('orderBy', 'created_at');
        $this->orderDir = $request->input('orderBy', 'desc');
        $this->checkOrdering();

        $links = $share->entity->links()
            ->publicOnly()
            ->orderBy($this->orderBy, $this->orderDir)
            ->paginate(getPaginationLimit());

        return view('guest.tags.show', [
            'pageTitle' => trans('tag.tag') . ': ' . $share->entity->name,
            'tag' => $share->entity,
            'links' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
        ]);
    }

    private function displayLink(PrivateShare $share)
    {
        $share->entity->lists = $share->entity->lists->filter(
            fn($list) => $list->visibility === ModelAttribute::VISIBILITY_PUBLIC
        );

        $share->entity->tags = $share->entity->tags->filter(
            fn($tag) => $tag->visibility === ModelAttribute::VISIBILITY_PUBLIC
        );

        return view('guest.links.show', [
            'pageTitle' => trans('link.link') . ': ' . $share->entity->shortTitle(),
            'link' => $share->entity,
        ]);
    }
}
