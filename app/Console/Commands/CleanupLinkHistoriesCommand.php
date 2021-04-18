<?php

namespace App\Console\Commands;

use App\Models\Link;
use Illuminate\Console\Command;
use Venturecraft\Revisionable\Revision;

class CleanupLinkHistoriesCommand extends Command
{
    protected $signature = 'links:cleanup-histories {field?}';

    protected $description = 'Removes all but the last 5 entries in the link histories.
        {field : If provided, only history entries of that field are deleted}';

    private $offset;

    public function handle(): void
    {
        $baseQuery = Revision::where('revisionable_type', Link::class);

        if ($this->argument('field')) {
            $baseQuery->where('key', $this->argument('field'));
        }

        $count = $baseQuery->count();

        if ($count === 0) {
            $this->warn(sprintf('No history entries%s found!', ($this->argument('field') ? ' for this field ' : '')));
            return;
        }

        $linkCount = $baseQuery->groupBy('revisionable_id')->count('revisionable_id');

        $this->info(" Found $count entries across $linkCount links.");

        if (!$this->confirm('Are you sure you want to remove these history entries?')) {
            return;
        }

        $this->offset = (int)$this->ask('How many history entries should be kept?', 5);

        $bar = $this->output->createProgressBar($linkCount);
        $bar->start();

        Link::withTrashed()->has('revisionHistory')->each(function (Link $link) use ($bar) {
            $historyEntries = $link->revisionHistory()->orderBy('created_at', 'desc')
                ->skip($this->offset)->take(9999999)
                ->pluck('id');

            Revision::whereIn('id', $historyEntries)->delete();
            $bar->advance();
        });

        $bar->finish();
        $this->line('');

        $this->info(" Successfully deleted $count entries.");
    }
}
