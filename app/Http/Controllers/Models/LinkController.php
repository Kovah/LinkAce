<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChecksOrdering;
use App\Http\Controllers\Traits\HandlesQueryOrder;
use App\Http\Requests\Models\LinkStoreRequest;
use App\Http\Requests\Models\LinkUpdateRequest;
use App\Http\Requests\Models\ToggleLinkCheckRequest;
use App\Models\Link;
use App\Repositories\LinkRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    use ChecksOrdering;

    public function __construct()
    {
        $this->allowedOrderBy = Link::$allowOrderBy;
        $this->authorizeResource(Link::class, 'link');
    }

    public function index(Request $request): View
    {
        $this->orderBy = $request->input('orderBy', session()->get('links.index.orderBy', 'created_at'));
        $this->orderDir = $request->input('orderDir', session()->get('links.index.orderDir', 'desc'));
        $this->checkOrdering();

        session()->put('links.index.orderBy', $this->orderBy);
        session()->put('links.index.orderDir', $this->orderDir);

        $links = Link::query()
            ->visibleForUser()
            ->with('tags');

        if ($this->orderBy === 'random') {
            $links->inRandomOrder();
        } else {
            $links->orderBy($this->orderBy, $this->orderDir);
        }

        return view('models.links.index', [
            'links' => $links->paginate(getPaginationLimit()),
            'route' => $request->getBaseUrl(),
            'orderBy' => $this->orderBy,
            'orderDir' => $this->orderDir,
        ]);
    }

    public function create(): View
    {
        // Reset the bookmarklet session identifier to prevent issues on regular pages
        session()->forget('bookmarklet.create');

        return view('models.links.create', [
            'existing_link' => null,
        ]);
    }

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
                    route('links.show', ['link' => $duplicateLink]),
                    $duplicateLink->shortUrl()
                );
            }

            flash(trim($msg, ','), 'warning');
        }

        $isBookmarklet = session('bookmarklet.create');

        if ($request->input('reload_view')) {
            return redirect()->route($isBookmarklet ? 'bookmarklet-add' : 'links.create')->with('reload_view', true);
        }

        return $isBookmarklet
            ? redirect()->route('bookmarklet-complete')
            : redirect()->route('links.show', [$link->id]);
    }

    public function show(Link $link): View
    {
        $link->load([
            'lists' => function ($query) {
                $query->visibleForUser();
            },
            'tags' => function ($query) {
                $query->visibleForUser();
            },
        ]);
        return view('models.links.show', [
            'link' => $link,
            'history' => $link->audits()->latest()->get(),
        ]);
    }

    public function edit(Link $link): View
    {
        return view('models.links.edit', [
            'link' => $link,
            'existing_link' => null,
        ]);
    }

    public function update(LinkUpdateRequest $request, Link $link): RedirectResponse
    {
        $link = LinkRepository::update($link, $request->input());

        flash(trans('link.updated_successfully'), 'success');
        return redirect()->route('links.show', [$link->id]);
    }

    public function destroy(Link $link): RedirectResponse
    {
        $deletionSuccessful = LinkRepository::delete($link);

        if (!$deletionSuccessful) {
            flash(trans('link.deletion_error'), 'error');
            return redirect()->back();
        }

        flash(trans('link.deleted_successfully'), 'warning');

        return request()->has('redirect_back') ? redirect()->back() : redirect()->route('links.index');
    }

    public function updateCheckToggle(ToggleLinkCheckRequest $request, Link $link): RedirectResponse
    {
        $link->check_disabled = $request->input('toggle');
        $link->save();

        return redirect()->route('links.show', ['link' => $link]);
    }

    public function markWorking(Link $link): RedirectResponse
    {
        $this->authorize('update', $link);

        $link->status = Link::STATUS_OK;
        $link->save();

        return redirect()->route('links.show', ['link' => $link]);
    }
}
