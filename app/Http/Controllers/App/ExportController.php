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
    public function getExport(): View
    {
        return view('app.export.export', [
            'pageTitle' => trans('export.export'),
        ]);
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
        $links = Link::whereUserId(auth()->id())->oldest('title')->get();

        $links->each(function (Link $link) {
            $link->setRelation('tags', $link->tags()->visibleForUser()->get());
        });

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
        $links = Link::whereUserId(auth()->id())->oldest('title')->get();

        $rows = $links->map(function (Link $link) {
            $link->tags = $link->tags()->visibleForUser()->get()->pluck('name')->join(',');
            $link->lists = $link->lists()->visibleForUser()->get()->pluck('name')->join(',');
            return $link;
        })->toArray();

        $rows = array_map(
            fn (array $row) => array_map($this->neutralizeCsvFormulaCell(...), $row),
            $rows
        );

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

    /**
     * Prevent CSV/formula injection (CWE-1236). Spreadsheet applications treat
     * cells starting with =, +, -, or @ as formulas, which can be abused for
     * remote command execution (legacy DDE) or data exfiltration (HYPERLINK,
     * WEBSERVICE, IMPORTXML, ...) when a user opens an exported file. Leading
     * whitespace is stripped before the check, since Excel evaluates formulas
     * after trimming and a naive "starts with" check on the raw value could
     * otherwise be bypassed by a leading space/tab.
     */
    private function neutralizeCsvFormulaCell(mixed $value): mixed
    {
        if (!is_string($value) || $value === '') {
            return $value;
        }

        $trimmed = ltrim($value, " \t\r\n\0\x0B");

        if ($trimmed !== '' && in_array($trimmed[0], ['=', '+', '-', '@'], true)) {
            return "'" . $value;
        }

        return $value;
    }
}
