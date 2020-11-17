<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class BookmarkletController extends Controller
{
    /**
     * Show the link creation form based on the information provided by the Bookmarklet.
     *
     * @param Request $request
     * @return Factory|RedirectResponse|View
     */
    public function getLinkAddForm(Request $request)
    {
        $newUrl = $request->input('u');
        $newTitle = $request->input('t');

        // Redirect to the login if the user is not logged in
        if (!auth()->check()) {
            // Save details for the link in the session
            session(['bookmarklet.new_url' => $newUrl]);
            session(['bookmarklet.new_title' => $newTitle]);
            session(['bookmarklet.login_redirect' => true]);

            return redirect()->route('bookmarklet-login');
        }

        if ($newUrl === null) {
            // Receive the link details from the session after the user logged in
            $newUrl = session()->pull('bookmarklet.new_url');
            $newTitle = session()->pull('bookmarklet.new_title');
        }

        session(['bookmarklet.create' => true]);

        return view('actions.bookmarklet.create', [
            'bookmark_url' => $newUrl,
            'bookmark_title' => $newTitle,
        ]);
    }

    /**
     * Display the confirmation screen after adding a new link.
     *
     * @return View
     */
    public function getCompleteView(): View
    {
        return view('actions.bookmarklet.complete');
    }

    /**
     * Return a special version of the login form made for the Bookmarklet.
     *
     * @return View
     */
    public function getLoginForm(): View
    {
        return view('actions.bookmarklet.login');
    }
}
