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
     * @param Request $request
     * @return StreamedResponse
     */
    public function doExport(Request $request): StreamedResponse
    {
        $links = Link::orderBy('title', 'asc')->get();

        $file_content = view()->make('actions.export.exportfile', ['links' => $links])->render();
        $file_name = config('app.name') . '_export.html';

        return response()->streamDownload(function () use ($file_content) {
            echo $file_content;
        }, $file_name);
    }
}
