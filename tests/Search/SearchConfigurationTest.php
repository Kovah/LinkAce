<?php

namespace Tests\Search;

use Tests\TestCase;

class SearchConfigurationTest extends TestCase
{
    public function test_search_driver_defaults_to_database(): void
    {
        $this->assertSame('database', config('linkace.search.driver'));
    }

    public function test_supported_search_drivers_are_declared(): void
    {
        $this->assertSame([
            'database',
            'meilisearch',
            'typesense',
        ], config('linkace.search.supported_drivers'));

        $this->assertSame([
            'meilisearch',
            'typesense',
        ], config('linkace.search.external_drivers'));
    }
}
