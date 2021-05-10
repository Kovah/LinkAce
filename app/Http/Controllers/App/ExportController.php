<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Get the initial screen to start the export.
     *
     * @return View
     */
    public function getExport(): View
    {
        return view('app.export.export');
    }

    /**
     * Export all links to a file. We use a blade template which contains the
     * basic layout for a Netscape HTML template which is the standard for
     * importing/exporting bookmarks in browsers. The rendered view is then
     * streamed to the user as a file download.
     *
     * @return StreamedResponse
     * @throws BindingResolutionException
     */
    public function doHtmlExport(): StreamedResponse
    {
        $links = Link::orderBy('title')->with('tags')->get();

        $fileContent = view()->make('app.export.html-export', ['links' => $links])->render();
        $fileName = config('app.name') . '_export.html';

        return response()->streamDownload(function () use ($fileContent) {
            echo $fileContent;
        }, $fileName);
    }

    /**
     * Export all links to a CSV file. Tags and lists are inlined with their
     * names. A CSV file is generated with the League\Csv\Writer and made
     * available to download.
     *
     * @return RedirectResponse|StreamedResponse
     */
    public function doCsvExport()
    {
        $links = Link::orderBy('title')->get();

        $rows = $links->map(function (Link $link) {
            $link->tags = $link->tags()->get()->pluck('name')->join(',');
            $link->lists = $link->lists()->get()->pluck('name')->join(',');
            return $link;
        })->toArray();

        try {
            $csv = Writer::createFromString();
            $csv->insertOne(array_keys($rows[0]));
            $csv->insertAll($rows);
        } catch (CannotInsertRecord $e) {
            Log::error($e->getMessage());
            flash(trans('export.export_csv_error'));

            return redirect()->back();
        }

        $fileName = config('app.name') . '_export.csv';

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $fileName);
    }
}
