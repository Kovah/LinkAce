<?php

namespace App\Http\Controllers\Models;

use App\Helper\LinkAce;
use App\Http\Controllers\Controller;
use App\Http\Requests\LinkDeleteRequest;
use App\Http\Requests\LinkStoreRequest;
use App\Http\Requests\LinkUpdateRequest;
use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('models.links.index')
            ->with('links', Link::byUser(auth()->user()->id)
                ->orderBy('created_at', 'DESC')
                ->paginate(config('linkace.default.pagination'))
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('models.links.create')
            ->with('categories', Category::parentOnly()
                ->byUser(auth()->user()->id)
                ->orderBy('name', 'asc')
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
        $data = $request->except(['tags', 'reload_view']);

        // Try to get the <title> of the URL if no title was provided
        $data['title'] = $data['title'] ?? LinkAce::getTitleFromURL($data['url']);

        // Set the user ID
        $data['user_id'] = auth()->user()->id;

        $data['category_id'] = isset($data['category_id']) && $data['category_id'] > 0 ?: null;

        // Create the new link
        $link = Link::create($data);

        // Get all tags
        if ($tags = $request->get('tags')) {
            $tags = explode(',', $tags);

            foreach ($tags as $tag) {
                $new_tag = Tag::firstOrCreate([
                    'user_id' => auth()->user()->id,
                    'name' => $tag,
                ]);

                $link->tags()->attach($new_tag->id);
            }
        }

        alert(trans('link.added_successfully'), 'success');

        if ($request->get('reload_view')) {
            session()->flash('reload_view', true);
            return redirect()->route('links.create');
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

        // Update the existing link with new data
        $link->update($request->except('tags'));

        // Update all tags
        if ($tags = $request->get('tags')) {
            $tags = collect(explode(',', $tags));
            $new_tags = [];

            foreach ($tags as $tag) {
                $new_tag = Tag::firstOrCreate([
                    'user_id' => auth()->user()->id,
                    'name' => $tag,
                ]);

                $new_tags[] = $new_tag->id;
            }

            $link->tags()->sync($new_tags);
        }

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

        $link->delete();

        alert(trans('link.deleted_successfully'), 'warning');

        return redirect()->route('links.index');
    }
}
