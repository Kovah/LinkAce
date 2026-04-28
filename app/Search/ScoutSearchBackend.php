<?php

namespace App\Search;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use LogicException;

class ScoutSearchBackend implements SearchBackend
{
    public function searchLinks(SearchQuery $query): LengthAwarePaginator
    {
        throw new LogicException('Scout search backend is not implemented yet.');
    }

    public function searchTags(Request $request): Collection
    {
        throw new LogicException('Scout search backend is not implemented yet.');
    }

    public function searchLists(Request $request): Collection
    {
        throw new LogicException('Scout search backend is not implemented yet.');
    }
}
