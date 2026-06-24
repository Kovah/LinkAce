<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Laravel\Scout\Contracts\UpdatesIndexSettings;
use Laravel\Scout\EngineManager;

class SearchSetupCommand extends Command
{
    protected $signature = 'search:setup';

    protected $description = 'Prepare the configured external search engine for LinkAce search.';

    public function handle(): int
    {
        $driver = config('linkace.search.driver');

        if ($driver === 'database') {
            $this->info('Database search is active. No external search setup is required.');

            return self::SUCCESS;
        }

        if (! in_array($driver, config('linkace.search.external_drivers', []), true)) {
            $this->error('Unsupported search driver configured: '.$driver);

            return self::FAILURE;
        }

        config(['scout.driver' => $driver]);

        if ($driver === 'meilisearch') {
            $this->info('Syncing Meilisearch index settings...');
            if (! $this->syncMeilisearchIndexSettings()) {
                return self::FAILURE;
            }

            $this->info('Meilisearch index settings synced.');

            return self::SUCCESS;
        }

        $models = implode(', ', $this->searchableModels());
        $this->info('Typesense collection schemas are configured for '.$models.'.');
        $this->info('Run search:rebuild to create or update Typesense collections during import.');

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

    private function syncMeilisearchIndexSettings(): bool
    {
        $engine = app(EngineManager::class)->engine('meilisearch');

        if (! $engine instanceof UpdatesIndexSettings) {
            $this->error('The configured Meilisearch engine does not support index settings sync.');

            return false;
        }

        foreach (config('scout.meilisearch.index-settings', []) as $index => $settings) {
            $engine->updateIndexSettings($this->indexName($index), $engine->configureSoftDeleteFilter($settings));
        }

        return true;
    }

    private function indexName(string $index): string
    {
        $prefix = config('scout.prefix');

        return ! Str::startsWith($index, $prefix) ? $prefix.$index : $index;
    }
}
