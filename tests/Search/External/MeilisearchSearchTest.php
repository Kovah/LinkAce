<?php

namespace Tests\Search\External;

use Meilisearch\Client as MeilisearchClient;
use Meilisearch\Contracts\TasksQuery;
use PHPUnit\Framework\Attributes\Group;

#[Group('external-search')]
class MeilisearchSearchTest extends ExternalSearchTestCase
{
    protected function driver(): string
    {
        return 'meilisearch';
    }

    protected function skipWhenEngineIsNotConfigured(): void
    {
        if (env('MEILISEARCH_HOST') === null) {
            $this->markTestSkipped('Set MEILISEARCH_HOST to run Meilisearch external search tests.');
        }
    }

    protected function waitForEngine(): void
    {
        $this->waitForMeilisearch();
    }

    protected function cleanupExternalSearchIndexes(): void
    {
        $this->cleanupMeilisearchIndexes();
    }

    protected function waitForIndexing(): void
    {
        $client = app(MeilisearchClient::class);
        $taskIds = [];

        foreach ($this->searchIndexNames() as $indexName) {
            $query = (new TasksQuery())
                ->setIndexUids([$this->searchIndexPrefix.$indexName])
                ->setStatuses(['enqueued', 'processing'])
                ->setLimit(200);

            foreach ($client->getTasks($query)->getResults() as $task) {
                $taskIds[] = $task['uid'];
            }
        }

        if ($taskIds !== []) {
            $client->waitForTasks($taskIds, 10000, 50);
        }
    }
}
