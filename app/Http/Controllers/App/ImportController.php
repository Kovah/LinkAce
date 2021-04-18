<?php

namespace App\Http\Controllers\App;

use App\Actions\ImportHtmlBookmarks;
use App\Http\Controllers\Controller;
use App\Http\Requests\DoImportRequest;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ImportController extends Controller
{
    /**
     * Display the initial screen to start the import.
     *
     * @return View
     */
    public function getImport(): View
    {
        return view('app.import.import');
    }

    /**
     * Load the provided HTML bookmarks file and save all parsed results as new
     * links including tags. This method is called via an Ajax call to prevent
     * timeouts during the link creation.
     *
     * @param DoImportRequest $request
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function doImport(DoImportRequest $request): JsonResponse
    {
        $data = $request->file('import-file')->get();

        $importer = new ImportHtmlBookmarks;
        $result = $importer->run($data, auth()->id());

        if ($result === false) {
            response()->json([
                'success' => false,
                'message' => trans('import.import_error'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('import.import_successfully', [
                'imported' => $importer->getImportCount(),
                'skipped' => $importer->getSkippedCount(),
            ]),
        ]);
    }
}
