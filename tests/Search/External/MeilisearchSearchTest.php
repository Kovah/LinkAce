<?php

namespace Tests\Search\External;

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
}
