<?php

namespace App\Console\Commands;

use App\Helper\HtmlMeta;
use App\Helper\LinkIconMapper;
use App\Models\Link;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Shaarli\NetscapeBookmarkParser\NetscapeBookmarkParser;

/**
 * Class ImportCommand
 *
 * @package App\Console\Commands
 */
class ImportCommand extends Command
{
    protected $signature = 'links:import {filename} {--skip-lookup} {--skip-check}';

    public function handle(): void
    {
        $lookup = true;
        $check = true;

        if ($this->argument('filename')) {
            $filename = $this->argument('filename');
        } else {
            return;
        }


        // Check if option "-skip-lookup" is present
        if ($this->option('skip-lookup')) {
            $this->info("Skipping lookup");
            $lookup = !$this->option('skip-lookup');
        }

        // Check if option "-skip-check" is present
        if ($this->option('skip-check')) {
            $this->info("Skipping link check");
            $check = !$this->option('skip-check');
        }


        // Read file
        $this->info('Reading file "' . $filename . '"...');
        $data = file_get_contents($filename);


        $parser = new NetscapeBookmarkParser(true, [], '0', storage_path('logs'));

        try {
            $links = $parser->parseString($data);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $this->error(trans('import.import_error'));
            return;
        }

        if (empty($links)) {
            // This will never be reached at the moment because the bookmark parser is not capable of handling
            // empty bookmarks exports. See https://github.com/shaarli/netscape-bookmark-parser/issues/50
            $this->error(trans('import.import_empty'));
            return;
        }

        $userId = 1;
        $imported = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar(count($links));

        $bar->start();


        foreach ($links as $link) {
            if (Link::whereUrl($link['uri'])->first()) {
                $skipped++;
                continue;
            }

            $bar->advance();

            if ($lookup) {
                $linkMeta = HtmlMeta::getFromUrl($link['uri']);

                $title = $link['title'] ?: $linkMeta['title'];

                $description = $link['note'] ?: $linkMeta['description'];
            } else {
                $title = $link['title'];

                $description = $link['note'];
            }

            $newLink = Link::create([
                'user_id' => $userId,
                'url' => $link['uri'],
                'title' => $title,
                'description' => $description,
                'icon' => LinkIconMapper::mapLink($link['uri']),
                'is_private' => $link['pub'],
                'created_at' => Carbon::createFromTimestamp($link['time']),
                'updated_at' => Carbon::now(),
            ]);

            // Get all tags
            if (!empty($link['tags'])) {
                $tags = explode(' ', $link['tags']);

                $newTags = [];
                foreach ($tags as $tag) {
                    $newTag = Tag::firstOrCreate([
                        'user_id' => $userId,
                        'name' => $tag,
                    ]);

                    $newTags[] = $newTag->id;
                }

                $newLink->tags()->sync($newTags);
            }

            $imported++;
        }

        $bar->finish();

        // Force new line
        $this->comment(PHP_EOL);

        if ($check) {
            // Queue link check
            Artisan::queue('links:check');
        }

        $this->info(trans('import.import_successfully', [
            'imported' => $imported,
            'skipped' => $skipped,
        ]));
    }
}
