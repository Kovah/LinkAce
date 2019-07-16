<?php

namespace App\Http\Controllers\Models;

use App\Helper\LinkAce;
use App\Helper\LinkIconMapper;
use App\Helper\WaybackMachine;
use App\Http\Controllers\Controller;
use App\Http\Requests\LinkDeleteRequest;
use App\Http\Requests\LinkStoreRequest;
use App\Http\Requests\LinkUpdateRequest;
use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;
use App\Repositories\LinkRepository;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $links = Link::byUser(auth()->id());

        if ($request->has('orderBy') && $request->has('orderDir')) {
            $links->orderBy($request->get('orderBy'), $request->get('orderDir'));
        } else {
            $links->orderBy('created_at', 'DESC');
        }

        $links = $links->paginate(getPaginationLimit());

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // Reset the bookmarklet session identifier to prevent issues on regular pages
        session()->forget('bookmarklet.create');

        return view('models.links.create')
            ->with('categories', Category::parentOnly()
                ->byUser(auth()->user()->id)
                ->orderBy('name')
                ->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LinkStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LinkStoreRequest $request)
    {
        // Save the new link
        $link = LinkRepository::create($request->all());

        alert(trans('link.added_successfully'), 'success');

        // Redirect to the corresponding page based on bookmarklet usage
        $is_bookmarklet = session('bookmarklet.create');

        if ($request->get('reload_view')) {
            session()->flash('reload_view', true);

            if ($is_bookmarklet) {
                return redirect()->route('bookmarklet-add');
            }

            return redirect()->route('links.create');
        }

        if ($is_bookmarklet) {
            return redirect()->route('bookmarklet-complete');
        }

        return redirect()->route('links.show', [$link->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $link = Link::find($id);

        if (empty($link)) {
            abort(404);
        }

        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        return view('models.links.show')
            ->with('link', $link);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $link = Link::find($id);

        if (empty($link)) {
            abort(404);
        }

        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        return view('models.links.edit')
            ->with('categories', Category::parentOnly()
                ->byUser(auth()->user()->id)
                ->orderBy('name', 'asc')
                ->get())
            ->with('link', $link);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LinkUpdateRequest $request
     * @param  int              $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LinkUpdateRequest $request, $id)
    {
        $link = Link::find($id);

        if (empty($link)) {
            abort(404);
        }

        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        $link = LinkRepository::update($link, $request->all());

        alert(trans('link.updated_successfully'), 'success');

        return redirect()->route('links.show', [$link->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param LinkDeleteRequest $request
     * @param  int              $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(LinkDeleteRequest $request, $id)
    {
        $link = Link::find($id);

        if (empty($link)) {
            abort(404);
        }

        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        $deletion_successfull = LinkRepository::delete($link);

        if ($deletion_successfull) {
            alert(trans('link.deletion_error'), 'error');
            return redirect()->back();
        }

        alert(trans('link.deleted_successfully'), 'warning');

        return redirect()->route('links.index');
    }
}
