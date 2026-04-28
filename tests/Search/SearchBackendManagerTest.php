<?php

namespace Tests\Search;

use App\Search\DatabaseSearchBackend;
use App\Search\ScoutSearchBackend;
use App\Search\SearchBackendManager;
use RuntimeException;
use Tests\TestCase;

class SearchBackendManagerTest extends TestCase
{
    public function test_database_driver_returns_database_backend(): void
    {
        config(['linkace.search.driver' => 'database']);

        $this->assertInstanceOf(
            DatabaseSearchBackend::class,
            app(SearchBackendManager::class)->backend()
        );
    }

    public function test_external_drivers_return_scout_backend(): void
    {
        foreach (['meilisearch', 'typesense'] as $driver) {
            config(['linkace.search.driver' => $driver]);

            $this->assertInstanceOf(
                ScoutSearchBackend::class,
                app(SearchBackendManager::class)->backend()
            );
        }
    }

    public function test_invalid_driver_throws_clear_configuration_exception(): void
    {
        config(['linkace.search.driver' => 'unsupported']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unsupported search driver configured.');

        app(SearchBackendManager::class)->backend();
    }
}
