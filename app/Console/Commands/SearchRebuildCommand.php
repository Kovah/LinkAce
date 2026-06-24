<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Console\Command;

class SearchRebuildCommand extends Command
{
    protected $signature = 'search:rebuild';

    protected $description = 'Flush and rebuild LinkAce external search indexes.';

    public function handle(): int
    {
        $driver = config('linkace.search.driver');

        if ($driver === 'database') {
            $this->info('Database search is active. No external search index rebuild is required.');

            return self::SUCCESS;
        }

        config(['scout.driver' => $driver]);

        if ($this->call('search:setup') !== self::SUCCESS) {
            return self::FAILURE;
        }

        foreach ($this->searchableModels() as $model) {
            $this->info('Flushing and importing '.$model.'...');
            $model::removeAllFromSearch();
            $model::makeAllSearchable();
        }

        $this->info('External search engines may finish indexing asynchronously after this command exits.');

        return self::SUCCESS;
    }

    /**
     * @return list<class-string>
     */
    private function searchableModels(): array
    {
        return [
            Link::class,
            Tag::class,
            LinkList::class,
        ];
    }
}
