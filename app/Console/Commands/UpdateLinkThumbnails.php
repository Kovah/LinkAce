<?php

namespace App\Console\Commands;

use App\Helper\HtmlMeta;
use App\Models\Link;
use Illuminate\Console\Command;

class UpdateLinkThumbnails extends Command
{
    protected $signature = 'links:update-thumbnails';

    protected $description = 'Updates the thumbnails for all existing links, done in batches.';

    public function handle(): void
    {
        $this->confirm('This command updates the thumbnail for all links with the status "ok". This can take a long time, depending on the amount of links you have saved. Do you want to proceed?');

        $totalCount = Link::where('status', Link::STATUS_OK)->count();
        $processedLinks = 0;

        if ($totalCount === 0) {
            $this->warn('No links with status "ok" found. Aborting');
        }

        $this->comment("Started processing of $totalCount links...");

        Link::where('status', Link::STATUS_OK)->latest()
            ->chunk(100, function ($links) use ($processedLinks, $totalCount) {
                foreach ($links as $link) {
                    $this->updateThumbnailForLink($link);
                    sleep(1); // Rate limiting of outgoing traffic
                }

                $processedLinks += count($links);
                $this->comment("Processed $processedLinks of $totalCount links.");
            });

        $this->info('Finished processing all links.');
    }

    protected function updateThumbnailForLink(Link $link): void
    {
        $meta = (new HtmlMeta)->getFromUrl($link->url);

        if ($meta['thumbnail'] !== null) {
            $link->thumbnail = $meta['thumbnail'];
            $link->save();
        }
    }
}
