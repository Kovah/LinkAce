<?php

namespace App\Http\Controllers\Guest;

use App\Enums\ModelAttribute;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Controllers\Traits\ConfiguresLinkDisplay;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ChecksOrdering;
    use ConfiguresLinkDisplay;

    public function __construct()
    {
        $this->allowedOrderBy = Tag::$allowOrderBy;
    }

    public function index(Request $request): View
    {
        $this->orderBy = $request->input('orderBy', 'created_at');
        $this->orderDir = $request->input('orderBy', 'desc');
        $this->checkOrdering();

        $tags = Tag::publicOnly()
            ->withCount(['links' => fn($query) => $query->publicOnly()])
            ->orderBy($this->orderBy, $this->orderDir);

        if ($request->input('filter')) {
            $tags = $tags->where('name', 'like', '%' . $request->input('filter') . '%');
        }

        $tags = $tags->paginate(getPaginationLimit());

        return view('guest.tags.index', [
            'pageTitle' => trans('tag.tags'),
            'tags' => $tags,
            'route' => $request->getBaseUrl(),
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
        ]);
    }

    public function show(Request $request, Tag $tag): View
    {
        if ($tag->visibility !== ModelAttribute::VISIBILITY_PUBLIC) {
            abort(404);
        }

        $this->updateLinkDisplayForGuest();

        $this->orderBy = $request->input('orderBy', 'created_at');
        $this->orderDir = $request->input('orderBy', 'desc');
        $this->checkOrdering();

        $links = $tag->links()->publicOnly();

        if ($this->orderBy === 'random') {
            $links->inRandomOrder();
        } else {
            $links->orderBy($this->orderBy, $this->orderDir);
        }

        $links = $links->paginate(getPaginationLimit());

        return view('guest.tags.show', [
            'pageTitle' => trans('tag.tag') . ': ' . $tag->name,
            'tag' => $tag,
            'links' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
        ]);
    }
}
