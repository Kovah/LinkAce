<?php

namespace App\Http\Controllers\App;

use App\Helper\LinkAce;
use App\Helper\LinkIconMapper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DoImportRequest;
use App\Models\Link;
use App\Models\Tag;
use Carbon\Carbon;
use Shaarli\NetscapeBookmarkParser\NetscapeBookmarkParser;

class ImportController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getImport()
    {
        return view('actions.import.import');
    }

    /**
     * Permanently delete entries for a model from the trash
     *
     * @param DoImportRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function doImport(DoImportRequest $request)
    {
        $data = $request->file('import-file')->get();

        $parser = new NetscapeBookmarkParser();

        $links = $parser->parseString($data);

        if (empty($links)) {
            alert(trans('import.import_empty'), 'warning');
            return redirect()->back();
        }

        $user_id = auth()->id();
        $imported = 0;
        $skipped = 0;

        foreach ($links as $link) {
            if (Link::whereUrl($link['uri'])->first()) {
                $skipped++;
            } else {

                $title = $link['title'] ?: LinkAce::getTitleFromURL($link['uri']);

                $new_link = Link::create([
                    'user_id' => $user_id,
                    'category_id' => null,
                    'url' => $link['uri'],
                    'title' => $title,
                    'description' => $link['note'],
                    'icon' => LinkIconMapper::mapLink($link['uri']),
                    'is_private' => $link['pub'],
                    'created_at' => Carbon::createFromTimestamp($link['time']),
                    'updated_at' => Carbon::now(),
                ]);

                Link::flushCache();

                // Get all tags
                if (!empty($link['tags'])) {
                    $tags = explode(' ', $link['tags']);

                    foreach ($tags as $tag) {
                        $new_tag = Tag::firstOrCreate([
                            'user_id' => $user_id,
                            'name' => $tag,
                        ]);

                        $new_link->tags()->attach($new_tag->id);

                        Tag::flushCache();
                    }
                }

                $imported++;
            }
        }

        alert(trans('import.import_successfully', [
            'imported' => $imported,
            'skipped' => $skipped,
        ]), 'success');

        return redirect()->back();
    }
}
