<?php

namespace App\Search;

use RuntimeException;

class SearchBackendManager
{
    public function backend(): SearchBackend
    {
        return match (config('linkace.search.driver')) {
            'database' => app(DatabaseSearchBackend::class),
            'meilisearch', 'typesense' => app(ScoutSearchBackend::class),
            default => throw new RuntimeException('Unsupported search driver configured.'),
        };
    }
}
