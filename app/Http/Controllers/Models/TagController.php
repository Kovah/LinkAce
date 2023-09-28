<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Controllers\Traits\HandlesQueryOrder;
use App\Http\Requests\Models\TagStoreRequest;
use App\Http\Requests\Models\TagUpdateRequest;
use App\Models\Link;
use App\Models\Tag;
use App\Repositories\TagRepository;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ChecksOrdering;

    public function __construct()
    {
        $this->allowedOrderBy = Tag::$allowOrderBy;
        $this->authorizeResource(Tag::class, 'tag');
    }

    public function index(Request $request): View
    {
        $orderBy = $request->input('orderBy', session()->get('tags.index.orderBy', 'name'));
        $orderDir = $this->getOrderDirection($request, session()->get('tags.index.orderDir', 'asc'));

        $this->checkOrdering();

        session()->put('tags.index.orderBy', $this->orderBy);
        session()->put('tags.index.orderDir', $this->orderDir);

        $tags = Tag::query()
            ->visibleForUser()
            ->withCount('links')
            ->orderBy($this->orderBy, $this->orderDir);

        if ($request->input('filter')) {
            $tags = $tags->where('name', 'like', '%' . $request->input('filter') . '%');
        }

        $tags = $tags->paginate(getPaginationLimit());

        return view('models.tags.index', [
            'tags' => $tags,
            'route' => $request->getBaseUrl(),
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
            'filter' => $request->input('filter'),
        ]);
    }

    public function create(): View
    {
        return view('models.tags.create');
    }

    public function store(TagStoreRequest $request): RedirectResponse
    {
        $tag = TagRepository::create($request->validated());

        flash(trans('tag.added_successfully'), 'success');

        if ($request->input('reload_view')) {
            return redirect()->route('tags.create')->with('reload_view', true);
        }

        return redirect()->route('tags.show', ['tag' => $tag]);
    }

    public function show(Request $request, Tag $tag): View
    {
        $this->allowedOrderBy = Link::$allowOrderBy;
        $this->orderBy = $request->input('orderBy', 'created_at');
        $this->orderDir = $this->getOrderDirection($request);

        $this->checkOrdering();

        $links = $tag->links()->visibleForUser()
            ->orderBy($this->orderBy, $this->orderDir)
            ->paginate(getPaginationLimit());

        return view('models.tags.show', [
            'tag' => $tag,
            'history' => $tag->audits()->latest()->get(),
            'tagLinks' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'created_at'),
            'orderDir' => $this->getOrderDirection($request),
        ]);
    }

    public function edit(Tag $tag): View
    {
        return view('models.tags.edit', ['tag' => $tag]);
    }

    public function update(TagUpdateRequest $request, Tag $tag): RedirectResponse
    {
        $tag = TagRepository::update($tag, $request->validated());

        flash(trans('tag.updated_successfully'), 'success');
        return redirect()->route('tags.show', ['tag' => $tag]);
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $deletionSuccessful = TagRepository::delete($tag);

        if (!$deletionSuccessful) {
            flash(trans('tag.deletion_error'), 'error');
            return redirect()->back();
        }

        flash(trans('tag.deleted_successfully'), 'warning');
        return request()->has('redirect_back') ? redirect()->back() : redirect()->route('tags.index');
    }
}
