<?php

namespace App\Search;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SearchBackend
{
    public function searchLinks(SearchQuery $query, ?LinkSearchScope $scope = null): LengthAwarePaginator;

    public function searchTags(Request $request): Collection;

    public function searchLists(Request $request): Collection;
}
