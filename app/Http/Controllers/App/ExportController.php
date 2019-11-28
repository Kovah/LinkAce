<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExportController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function getExport()
    {
        return view('actions.export.export');
    }

    /**
     * Permanently delete entries for a model from the trash
     *
     * @param Request $request
     * @return Response
     */
    public function doExport(Request $request)
    {
        $links = Link::orderBy('title', 'asc')->get();

        $file_content = view()->make('actions.export.exportfile', ['links' => $links])->render();
        $file_name = config('app.name') . '_export.html';

        return response()->streamDownload(function () use ($file_content) {
            echo $file_content;
        }, $file_name);
    }
}
