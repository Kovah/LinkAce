<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagDeleteRequest;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('guest.tags.index')
            ->with('tags', Tag::orderBy('name', 'ASC')
                ->paginate(getPaginationLimit())
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $tag = Tag::find($id);

        if (empty($tag)) {
            abort(404);
        }

        return view('guest.tags.show')
            ->with('tag', $tag)
            ->with('tag_links', $tag->links()
                ->paginate(getPaginationLimit())
            );
    }
}
