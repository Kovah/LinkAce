<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Requests\Models\TagStoreRequest;
use App\Http\Requests\Models\TagUpdateRequest;
use App\Models\Tag;
use App\Repositories\TagRepository;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ChecksOrdering;

    protected array $allowedOrders = [
        'created_at',
        'name',
        'links_count',
    ];

    public function __construct()
    {
        $this->authorizeResource(Tag::class, 'tag');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $this->orderBy = $request->input('orderBy', session()->get('tags.index.orderBy', 'name'));
        $this->orderDir = $request->input('orderDir', session()->get('tags.index.orderDir', 'asc'));

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

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('models.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TagStoreRequest $request
     * @return RedirectResponse
     */
    public function store(TagStoreRequest $request): RedirectResponse
    {
        $tag = TagRepository::create($request->validated());

        flash(trans('tag.added_successfully'), 'success');

        if ($request->input('reload_view')) {
            return redirect()->route('tags.create')->with('reload_view', true);
        }

        return redirect()->route('tags.show', ['tag' => $tag]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Tag     $tag
     * @return View
     */
    public function show(Request $request, Tag $tag): View
    {
        // @TODO Check ordering for links
        $links = $tag->links()->byUser()
            ->orderBy(
                $request->input('orderBy', 'created_at'),
                $request->input('orderDir', 'desc')
            )
            ->paginate(getPaginationLimit());

        return view('models.tags.show', [
            'tag' => $tag,
            'history' => $tag->audits()->latest()->get(),
            'tagLinks' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'created_at'),
            'orderDir' => $request->input('orderDir', 'desc'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tag $tag
     * @return View
     */
    public function edit(Tag $tag): View
    {
        return view('models.tags.edit', ['tag' => $tag]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TagUpdateRequest $request
     * @param Tag              $tag
     * @return RedirectResponse
     */
    public function update(TagUpdateRequest $request, Tag $tag): RedirectResponse
    {
        $tag = TagRepository::update($tag, $request->validated());

        flash(trans('tag.updated_successfully'), 'success');
        return redirect()->route('tags.show', ['tag' => $tag]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tag $tag
     * @return RedirectResponse
     * @throws Exception
     */
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
