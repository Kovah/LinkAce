<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class LinkController
 *
 * @package App\Http\Controllers\Guest
 */
class LinkController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $links = Link::privateOnly(false)
            ->paginate(getPaginationLimit());

        return view('guest.links.index', [
            'links' => $links,
        ]);
    }
}
