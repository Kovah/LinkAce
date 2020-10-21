<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\TagDeleteRequest;
use App\Http\Requests\Models\TagStoreRequest;
use App\Http\Requests\Models\TagUpdateRequest;
use App\Models\Tag;
use App\Repositories\TagRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $tags = Tag::byUser(auth()->id())
            ->withCount('links')
            ->orderBy(
                $request->input('orderBy', 'name'),
                $request->input('orderDir', 'ASC')
            )->paginate(getPaginationLimit());

        return view('models.tags.index', [
            'tags' => $tags,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'name'),
            'orderDir' => $request->input('orderDir', 'ASC'),
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
    public function store(TagStoreRequest $request)
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
     * @param int     $id
     * @return View
     */
    public function show(Request $request, $id): View
    {
        $tag = Tag::findOrFail($id);

        $links = $tag->links()->byUser(auth()->id())
            ->orderBy(
                $request->input('orderBy', 'created_at'),
                $request->input('orderDir', 'desc')
            )
            ->paginate(getPaginationLimit());

        return view('models.tags.show', [
            'tag' => $tag,
            'tagLinks' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'created_at'),
            'orderDir' => $request->input('orderDir', 'desc'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function edit($id): View
    {
        $tag = Tag::findOrFail($id);

        return view('models.tags.edit')->with('tag', $tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TagUpdateRequest $request
     * @param int              $id
     * @return RedirectResponse
     */
    public function update(TagUpdateRequest $request, $id): RedirectResponse
    {
        $tag = Tag::findOrFail($id);

        $data = $request->all();
        $tag = TagRepository::update($tag, $data);

        flash(trans('tag.updated_successfully'), 'success');
        return redirect()->route('tags.show', [$tag->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TagDeleteRequest $request
     * @param int              $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(TagDeleteRequest $request, $id): RedirectResponse
    {
        $tag = Tag::findOrFail($id);

        $deletionSuccessfull = TagRepository::delete($tag);

        if (!$deletionSuccessfull) {
            flash(trans('tag.deletion_error'), 'error');
            return redirect()->back();
        }

        flash(trans('tag.deleted_successfully'), 'warning');
        return redirect()->route('tags.index');
    }
}
