<?php

namespace App\Http\Controllers\App;

use App\Actions\ImportHtmlBookmarks;
use App\Http\Controllers\Controller;
use App\Http\Requests\DoImportRequest;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function form(): View
    {
        return view('app.import.import', [
            'pageTitle' => trans('import.import'),
        ]);
    }

    public function queue(Request $request): View
    {
        return view('app.import.queue', [
            'pageTitle' => trans('import.import'),
            'jobs' => $this->userImportJobs('jobs', $request->user()->id),
            'failed_jobs' => $this->userImportJobs('failed_jobs', $request->user()->id),
        ]);
    }

    /**
     * The jobs and failed_jobs tables are shared across all users and have no
     * user_id column, so ownership must be read from the serialized job payload.
     */
    private function userImportJobs(string $table, int $userId): LengthAwarePaginator
    {
        $jobs = DB::table($table)->where('queue', 'import')->get()
            ->filter(fn ($job) => unserialize(json_decode($job->payload)->data->command)->userId === $userId)
            ->values();

        $page = Paginator::resolveCurrentPage();

        return new LengthAwarePaginator(
            $jobs->forPage($page, 50),
            $jobs->count(),
            50,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
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

        $tag = $importer->getImportTag();

        return response()->json([
            'success' => true,
            'message' => trans('import.import_successfully', [
                'queued' => $importer->getQueuedCount(),
                'skipped' => $importer->getSkippedCount(),
                'taglink' => sprintf('<a href="%s">%s</a>', route('tags.show', ['tag' => $tag]), $tag->name),
            ]),
        ]);
    }
}
