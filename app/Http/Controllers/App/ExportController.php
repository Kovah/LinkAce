<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class ExportController
 *
 * @package App\Http\Controllers\App
 */
class ExportController extends Controller
{
    /**
     * @return Factory|View
     */
    public function getExport()
    {
        return view('actions.export.export');
    }

    /**
     * Export all links to a file. We use a blade template which contains the
     * basic layout for a Netscape HTML template which is the standard for
     * importing/exporting bookmarks in browsers. The rendered view is then
     * streamed to the user as a file download.
     *
     * @param Request $request
     * @return StreamedResponse
     */
    public function doExport(Request $request): StreamedResponse
    {
        $links = Link::orderBy('title', 'asc')->with('tags')->get();

        $fileContent = view()->make('actions.export.exportfile', ['links' => $links])->render();
        $fileName = config('app.name') . '_export.html';

        return response()->streamDownload(function () use ($fileContent) {
            echo $fileContent;
        }, $fileName);
    }
}
