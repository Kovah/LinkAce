<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\LinkDeleteRequest;
use App\Http\Requests\Models\LinkStoreRequest;
use App\Http\Requests\Models\LinkToggleCheckRequest;
use App\Http\Requests\Models\LinkUpdateRequest;
use App\Models\Link;
use App\Repositories\LinkRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class LinkController
 *
 * @package App\Http\Controllers\Models
 */
class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $links = Link::byUser(auth()->id())
            ->orderBy(
                $request->get('orderBy', 'created_at'),
                $request->get('orderDir', 'DESC')
            )
            ->paginate(getPaginationLimit());

        return view('models.links.index', [
            'links' => $links,
            'route' => $request->getBaseUrl(),
            'order_by' => $request->get('orderBy'),
            'order_dir' => $request->get('orderDir'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        // Reset the bookmarklet session identifier to prevent issues on regular pages
        session()->forget('bookmarklet.create');

        return view('models.links.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LinkStoreRequest $request
     * @return RedirectResponse
     */
    public function store(LinkStoreRequest $request)
    {
        $link = LinkRepository::create($request->all());

        alert(trans('link.added_successfully'), 'success');

        $isBookmarklet = session('bookmarklet.create');

        if ($request->get('reload_view')) {
            session()->flash('reload_view', true);

            if ($isBookmarklet) {
                return redirect()->route('bookmarklet-add');
            }

            return redirect()->route('links.create');
        }

        if ($isBookmarklet) {
            return redirect()->route('bookmarklet-complete');
        }

        return redirect()->route('links.show', [$link->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Factory|View
     */
    public function show($id)
    {
        $link = Link::findOrFail($id);

        return view('models.links.show')
            ->with('link', $link);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $link = Link::findOrFail($id);

        return view('models.links.edit')
            ->with('link', $link);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LinkUpdateRequest $request
     * @param int               $id
     * @return RedirectResponse
     */
    public function update(LinkUpdateRequest $request, $id)
    {
        $link = Link::findOrFail($id);

        $link = LinkRepository::update($link, $request->all());

        alert(trans('link.updated_successfully'), 'success');

        return redirect()->route('links.show', [$link->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param LinkDeleteRequest $request
     * @param int               $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(LinkDeleteRequest $request, $id)
    {
        $link = Link::findOrFail($id);

        $deletionSuccessfull = LinkRepository::delete($link);

        if (!$deletionSuccessfull) {
            alert(trans('link.deletion_error'), 'error');
            return redirect()->back();
        }

        alert(trans('link.deleted_successfully'), 'warning');
        return redirect()->route('links.index');
    }

    public function updateCheckToggle(LinkToggleCheckRequest $request, $id)
    {
        $link = Link::findOrFail($id);

        $link->check_disabled = $request->input('toggle');
        $link->save();

        return redirect()->route('links.show', [$link->id]);
    }
}
