<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\LinkList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookmarkletController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return Response
     */
    public function getLinkAddForm(Request $request)
    {
        $new_url = $request->get('u');
        $new_title = $request->get('t');

        // Rredirect to the login if the user is not logged in
        if (!auth()->check()) {
            // Save details for the link in the session
            session(['bookmarklet.new_url' => $new_url]);
            session(['bookmarklet.new_title' => $new_title]);
            session(['bookmarklet.login_redirect' => true]);

            return redirect()->route('bookmarklet-login');
        }

        if ($new_url === null) {
            // Receive the link details from the session
            $new_url = session('bookmarklet.new_url');
            $new_title = session('bookmarklet.new_title');

            session()->remove('bookmarklet.new_url');
            session()->remove('bookmarklet.new_title');
        }

        session(['bookmarklet.create' => true]);

        return view('actions.bookmarklet.create')
            ->with('bookmark_url', $new_url)
            ->with('bookmark_title', $new_title);
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function getCompleteView()
    {
        return view('actions.bookmarklet.complete');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function getLoginForm()
    {
        return view('actions.bookmarklet.login');
    }
}
