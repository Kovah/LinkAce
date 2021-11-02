<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        $newDescription = $request->input('d');
        $newTags = $request->input('tags');
        $newLists = $request->input('lists');

        // Redirect to the login if the user is not logged in
        if (!auth()->check()) {
            // Save details for the link in the session
            session(['bookmarklet.new_url' => $newUrl]);
            session(['bookmarklet.new_title' => $newTitle]);
            session(['bookmarklet.new_description' => $newDescription]);
            session(['bookmarklet.new_tags' => $newTags]);
            session(['bookmarklet.new_lists' => $newLists]);
            session(['bookmarklet.login_redirect' => true]);

            return redirect()->route('bookmarklet-login');
        }

        if ($newUrl === null) {
            // Receive the link details from the session after the user logged in
            $newUrl = session()->pull('bookmarklet.new_url');
            $newTitle = session()->pull('bookmarklet.new_title');
            $newDescription = session()->pull('bookmarklet.new_description');
            $newTags = session()->pull('bookmarklet.new_tags');
            $newLists = session()->pull('bookmarklet.new_lists');
        }

        session(['bookmarklet.create' => true]);

        return view('app.bookmarklet.create', [
            'existing_link' => Link::withTrashed()->whereUrl($newUrl)->first() ?: false,
            'bookmark_url' => $newUrl,
            'bookmark_title' => $newTitle,
            'bookmark_description' => $newDescription,
            'bookmark_tags' => $newTags,
            'bookmark_lists' => $newLists,
        ]);
    }

    /**
     * Display the confirmation screen after adding a new link.
     *
     * @return View
     */
    public function getCompleteView(): View
    {
        return view('app.bookmarklet.complete');
    }

    /**
     * Return a special version of the login form made for the Bookmarklet.
     *
     * @return View
     */
    public function getLoginForm(): View
    {
        return view('app.bookmarklet.login');
    }
}
