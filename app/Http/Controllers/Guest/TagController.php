<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class TagController
 *
 * @package App\Http\Controllers\Guest
 */
class TagController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int     $id
     * @return Factory|View
     */
    public function show(Request $request, $id)
    {
        $tag = Tag::find($id);

        if (empty($tag)) {
            abort(404);
        }

        $links = $tag->links()->privateOnly(false);

        if ($request->has('orderBy') && $request->has('orderDir')) {
            $links->orderBy($request->get('orderBy'), $request->get('orderDir'));
        } else {
            $links->orderBy('created_at', 'DESC');
        }

        $links = $links->paginate(getPaginationLimit());

        return view('guest.tags.show', [
            'tag' => $tag,
            'tag_links' => $links,
            'route' => $request->getBaseUrl(),
            'order_by' => $request->get('orderBy'),
            'order_dir' => $request->get('orderDir'),
        ]);
    }
}
