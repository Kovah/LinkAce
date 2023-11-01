<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HandlesQueryOrder;
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
    use HandlesQueryOrder;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $orderBy = $request->input('orderBy', session()->get('tags.index.orderBy', 'name'));
        $orderDir = $this->getOrderDirection($request, session()->get('tags.index.orderDir', 'asc'));

        session()->put('tags.index.orderBy', $orderBy);
        session()->put('tags.index.orderDir', $orderDir);

        $tags = Tag::byUser()
            ->withCount('links')
            ->orderBy($orderBy, $orderDir);

        if ($request->input('filter')) {
            $tags = $tags->where('name', 'like', '%' . $request->input('filter') . '%');
        }

        $tags = $tags->paginate(getPaginationLimit());

        return view('models.tags.index', [
            'pageTitle' => trans('tag.tags'),
            'tags' => $tags,
            'route' => $request->getBaseUrl(),
            'orderBy' => $orderBy,
            'orderDir' => $orderDir,
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
        return view('models.tags.create', [
            'pageTitle' => trans('tag.add'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TagStoreRequest $request
     * @return RedirectResponse
     */
    public function store(TagStoreRequest $request): RedirectResponse
    {
        $data = $request->except(['reload_view']);

        $tag = TagRepository::create($data);

        flash(trans('tag.added_successfully'), 'success');

        if ($request->input('reload_view')) {
            session()->flash('reload_view', true);
            return redirect()->route('tags.create');
        }

        return redirect()->route('tags.show', [$tag->id]);
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
        $links = $tag->links()->byUser()
            ->orderBy(
                $request->input('orderBy', 'created_at'),
                $this->getOrderDirection($request),
            )
            ->paginate(getPaginationLimit());

        return view('models.tags.show', [
            'pageTitle' => trans('tag.tag') . ': ' . $tag->name,
            'tag' => $tag,
            'tagLinks' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'created_at'),
            'orderDir' => $this->getOrderDirection($request),
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
        return view('models.tags.edit', [
            'pageTitle' => trans('tag.edit') . ': ' . $tag->name,
            'tag' => $tag,
        ]);
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
        $tag = TagRepository::update($tag, $request->input());

        flash(trans('tag.updated_successfully'), 'success');
        return redirect()->route('tags.show', [$tag->id]);
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
