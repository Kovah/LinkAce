<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\Models\LinkStoreRequest;
use App\Http\Requests\Models\LinkToggleCheckRequest;
use App\Http\Requests\Models\LinkUpdateRequest;
use App\Models\Link;
use App\Repositories\LinkRepository;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $links = Link::byUser(auth()->id())
            ->with('tags')
            ->orderBy(
                $request->input('orderBy', 'created_at'),
                $request->input('orderDir', 'desc')
            )
            ->paginate(getPaginationLimit());

        return view('models.links.index', [
            'links' => $links,
            'route' => $request->getBaseUrl(),
            'orderBy' => $request->input('orderBy', 'created_at'),
            'orderDir' => $request->input('orderDir', 'desc'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
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
    public function store(LinkStoreRequest $request): RedirectResponse
    {
        $link = LinkRepository::create($request->all(), true);

        flash(trans('link.added_successfully'), 'success');

        $duplicates = $link->searchDuplicateUrls();
        if ($duplicates->isNotEmpty()) {
            $msg = trans('link.duplicates_found');

            foreach ($duplicates as $duplicateLink) {
                $msg .= sprintf(
                    ' <a href="%s">%s</a>,',
                    route('links.show', [$duplicateLink->id]),
                    $duplicateLink->shortUrl()
                );
            }

            flash(trim($msg, ','), 'warning');
        }

        $isBookmarklet = session('bookmarklet.create');

        if ($request->input('reload_view')) {
            session()->flash('reload_view', true);
            return redirect()->route($isBookmarklet ? 'bookmarklet-add' : 'links.create');
        }

        return $isBookmarklet
            ? redirect()->route('bookmarklet-complete')
            : redirect()->route('links.show', [$link->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param Link $link
     * @return View
     */
    public function show(Link $link): View
    {
        return view('models.links.show', [
            'link' => $link,
            'history' => $link->revisionHistory()->latest()->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Link $link
     * @return View
     */
    public function edit(Link $link): View
    {
        return view('models.links.edit', [
            'link' => $link,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LinkUpdateRequest $request
     * @param Link              $link
     * @return RedirectResponse
     */
    public function update(LinkUpdateRequest $request, Link $link): RedirectResponse
    {
        $link = LinkRepository::update($link, $request->input());

        flash(trans('link.updated_successfully'), 'success');
        return redirect()->route('links.show', [$link->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Link $link
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Link $link): RedirectResponse
    {
        $deletionSuccessful = LinkRepository::delete($link);

        if (!$deletionSuccessful) {
            flash(trans('link.deletion_error'), 'error');
            return redirect()->back();
        }

        flash(trans('link.deleted_successfully'), 'warning');
        return redirect()->route('links.index');
    }

    /**
     * Toggles the setting of a link to be either checked or not.
     *
     * @param LinkToggleCheckRequest $request
     * @param Link                   $link
     * @return RedirectResponse
     */
    public function updateCheckToggle(LinkToggleCheckRequest $request, Link $link): RedirectResponse
    {
        $link->check_disabled = $request->input('toggle');
        $link->save();

        return redirect()->route('links.show', [$link->id]);
    }

    /**
     * Mark the link as working manually.
     *
     * @param Link $link
     * @return RedirectResponse
     */
    public function markWorking(Link $link): RedirectResponse
    {
        $link->status = Link::STATUS_OK;
        $link->save();

        return redirect()->route('links.show', [$link->id]);
    }
}
