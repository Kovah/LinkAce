<?php

namespace Tests\Search;

use App\Search\SearchQuery;
use Illuminate\Http\Request;
use Tests\TestCase;

class SearchQueryTest extends TestCase
{
    public function test_parses_plain_query(): void
    {
        $query = SearchQuery::fromRequest(new Request([
            'query' => 'example',
        ]));

        $this->assertSame('example', $query->query);
        $this->assertTrue($query->hasTextQuery());
        $this->assertFalse($query->hasFiltersOnly());
        $this->assertSame(['url'], $query->searchableLinkAttributes());
    }

    public function test_parses_title_and_description_search_flags(): void
    {
        $query = SearchQuery::fromRequest(new Request([
            'query' => 'example',
            'search_title' => '1',
            'search_description' => 'on',
        ]));

        $this->assertTrue($query->searchTitle);
        $this->assertTrue($query->searchDescription);
        $this->assertSame(['url', 'title', 'description'], $query->searchableLinkAttributes());
    }

    public function test_parses_visibility_and_broken_filter(): void
    {
        $query = SearchQuery::fromRequest(new Request([
            'query' => 'example',
            'visibility' => '2',
            'broken_only' => '1',
        ]));

        $this->assertSame(2, $query->visibility);
        $this->assertTrue($query->brokenOnly);
    }

    public function test_parses_comma_separated_list_and_tag_filters(): void
    {
        $query = SearchQuery::fromRequest(new Request([
            'only_lists' => '1,2,3',
            'only_tags' => '4,5',
        ]));

        $this->assertSame([1, 2, 3], $query->lists);
        $this->assertSame([4, 5], $query->tags);
        $this->assertFalse($query->hasTextQuery());
        $this->assertTrue($query->hasFiltersOnly());
    }

    public function test_parses_taxonomy_modes_and_exclusions(): void
    {
        $query = SearchQuery::fromRequest(new Request([
            'tag_mode' => 'all',
            'list_mode' => 'all',
            'exclude_tags' => '6,7',
            'exclude_lists' => '[8,9]',
        ]));

        $this->assertSame('all', $query->tagMode);
        $this->assertSame('all', $query->listMode);
        $this->assertSame([6, 7], $query->excludeTags);
        $this->assertSame([8, 9], $query->excludeLists);
        $this->assertTrue($query->hasFiltersOnly());
    }

    public function test_defaults_taxonomy_modes_to_any_for_api_backward_compatibility(): void
    {
        $query = SearchQuery::fromRequest(new Request([
            'only_lists' => '1,2',
            'only_tags' => '3,4',
        ]));

        $this->assertSame('any', $query->tagMode);
        $this->assertSame('any', $query->listMode);
        $this->assertSame([], $query->excludeTags);
        $this->assertSame([], $query->excludeLists);
    }

    public function test_parses_json_list_and_tag_filters(): void
    {
        $query = SearchQuery::fromRequest(new Request([
            'only_lists' => '[7,8]',
            'only_tags' => '["9","10"]',
        ]));

        $this->assertSame([7, 8], $query->lists);
        $this->assertSame([9, 10], $query->tags);
    }

    public function test_parses_empty_taxonomy_filters(): void
    {
        $query = SearchQuery::fromRequest(new Request([
            'empty_lists' => 'on',
            'empty_tags' => '1',
        ]));

        $this->assertTrue($query->emptyLists);
        $this->assertTrue($query->emptyTags);
        $this->assertTrue($query->hasFiltersOnly());
    }

    public function test_parses_supported_order_by_value(): void
    {
        $query = SearchQuery::fromRequest(new Request([
            'query' => 'example',
            'order_by' => 'created_at:desc',
        ]));

        $this->assertSame('created_at:desc', $query->orderBy);
    }

    public function test_falls_back_to_default_order_for_unsupported_order_by_value(): void
    {
        $query = SearchQuery::fromRequest(new Request([
            'query' => 'example',
            'order_by' => 'status:desc',
        ]));

        $this->assertSame('title:asc', $query->orderBy);
    }
}
