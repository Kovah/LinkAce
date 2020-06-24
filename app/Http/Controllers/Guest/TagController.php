<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int     $id
     * @return View
     */
    public function show(Request $request, $id): View
    {
        $tag = Tag::isPrivate(false)->findOrFail($id);

        $links = $tag->links()
            ->privateOnly(false)
            ->orderBy(
                $request->input('orderBy', 'name'),
                $request->input('orderDir', 'ASC')
            )->paginate(getPaginationLimit());

        return view('guest.tags.show', [
            'tag' => $tag,
            'tag_links' => $links,
            'route' => $request->getBaseUrl(),
            'order_by' => $request->input('orderBy', 'name'),
            'order_dir' => $request->input('orderDir', 'ASC'),
        ]);
    }
}
