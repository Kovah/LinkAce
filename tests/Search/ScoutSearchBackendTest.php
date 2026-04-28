<?php

namespace Tests\Search;

use App\Exceptions\ExternalSearchUnavailableException;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use App\Search\ScoutSearchBackend;
use App\Search\SearchQuery;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Builder as ScoutBuilder;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use RuntimeException;
use Tests\TestCase;

class ScoutSearchBackendTest extends TestCase
{
    use RefreshDatabase;

    private RecordingSearchEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->engine = new RecordingSearchEngine();
        $this->app->instance(EngineManager::class, new class ($this->engine) {
            public function __construct(private readonly Engine $engine)
            {
            }

            public function engine(): Engine
            {
                return $this->engine;
            }
        });
    }

    public function test_link_search_applies_meilisearch_options_filters_and_sorting(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        config(['linkace.search.driver' => 'meilisearch']);

        $query = new SearchQuery(
            query: 'flower',
            searchTitle: true,
            searchDescription: true,
            visibility: 2,
            brokenOnly: true,
            lists: [10, 11],
            tags: [12],
            emptyLists: false,
            emptyTags: false,
            orderBy: 'created_at:desc',
        );

        app(ScoutSearchBackend::class)->searchLinks($query);

        $builder = $this->engine->lastPaginatedBuilder;

        $this->assertSame('flower', $builder->query);
        $this->assertSame(['attributesToSearchOn' => ['url', 'title', 'description']], $builder->options);
        $this->assertContains(['field' => 'visibility', 'operator' => '=', 'value' => 2], $builder->wheres);
        $this->assertContains(['field' => 'status', 'operator' => '>', 'value' => 1], $builder->wheres);
        $this->assertSame([10, 11], $builder->whereIns['list_ids']);
        $this->assertSame([12], $builder->whereIns['tag_ids']);
        $this->assertSame([['column' => 'created_at', 'direction' => 'desc']], $builder->orders);
        $this->assertNotNull($builder->queryCallback);
    }

    public function test_link_search_applies_typesense_options(): void
    {
        $this->actingAs(User::factory()->create());
        config(['linkace.search.driver' => 'typesense']);

        $query = new SearchQuery(
            query: 'budha pest',
            searchTitle: false,
            searchDescription: true,
            visibility: null,
            brokenOnly: false,
            lists: [],
            tags: [],
            emptyLists: true,
            emptyTags: true,
            orderBy: null,
        );

        app(ScoutSearchBackend::class)->searchLinks($query);

        $builder = $this->engine->lastPaginatedBuilder;

        $this->assertSame([
            'query_by' => 'url,description',
            'split_join_tokens' => 'fallback',
            'drop_tokens_threshold' => 0,
        ], $builder->options);
        $this->assertContains(['field' => 'lists_count', 'operator' => '=', 'value' => 0], $builder->wheres);
        $this->assertContains(['field' => 'tags_count', 'operator' => '=', 'value' => 0], $builder->wheres);
    }

    public function test_random_ordering_delegates_to_database_backend(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        config(['linkace.search.driver' => 'meilisearch']);

        $link = Link::factory()->create([
            'user_id' => $user->id,
            'url' => 'https://example.com',
        ]);

        $query = new SearchQuery(
            query: 'example',
            searchTitle: false,
            searchDescription: false,
            visibility: null,
            brokenOnly: false,
            lists: [],
            tags: [],
            emptyLists: false,
            emptyTags: false,
            orderBy: 'random',
        );

        $results = app(ScoutSearchBackend::class)->searchLinks($query);

        $this->assertInstanceOf(LengthAwarePaginator::class, $results);
        $this->assertTrue($results->getCollection()->contains('id', $link->id));
        $this->assertNull($this->engine->lastPaginatedBuilder);
    }

    public function test_filter_only_link_search_delegates_to_database_backend(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        config(['linkace.search.driver' => 'meilisearch']);

        $link = Link::factory()->create([
            'user_id' => $user->id,
            'status' => Link::STATUS_BROKEN,
        ]);

        $query = new SearchQuery(
            query: null,
            searchTitle: false,
            searchDescription: false,
            visibility: null,
            brokenOnly: true,
            lists: [],
            tags: [],
            emptyLists: false,
            emptyTags: false,
            orderBy: null,
        );

        $results = app(ScoutSearchBackend::class)->searchLinks($query);

        $this->assertTrue($results->getCollection()->contains('id', $link->id));
        $this->assertNull($this->engine->lastPaginatedBuilder);
    }

    public function test_link_search_reloads_results_through_visibility_scoped_database_query(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->actingAs($user);
        config(['linkace.search.driver' => 'meilisearch']);

        $visibleLink = Link::factory()->create([
            'user_id' => $user->id,
            'url' => 'https://visible.example.com',
        ]);
        $hiddenLink = Link::factory()->create([
            'user_id' => $otherUser->id,
            'url' => 'https://hidden.example.com',
            'visibility' => 3,
        ]);
        $this->engine->hitIds = [$visibleLink->id, $hiddenLink->id];

        $query = new SearchQuery(
            query: 'example',
            searchTitle: false,
            searchDescription: false,
            visibility: null,
            brokenOnly: false,
            lists: [],
            tags: [],
            emptyLists: false,
            emptyTags: false,
            orderBy: null,
        );

        $results = app(ScoutSearchBackend::class)->searchLinks($query);

        $this->assertTrue($results->getCollection()->contains('id', $visibleLink->id));
        $this->assertFalse($results->getCollection()->contains('id', $hiddenLink->id));
    }

    public function test_tag_and_list_autocomplete_use_scout_with_user_scope(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        config(['linkace.search.driver' => 'meilisearch']);

        $tag = Tag::factory()->create(['user_id' => $user->id, 'name' => 'Programming']);
        $list = LinkList::factory()->create(['user_id' => $user->id, 'name' => 'Articles']);
        $this->engine->hitIds = [$tag->id, $list->id];

        $tagResults = app(ScoutSearchBackend::class)->searchTags(request()->merge(['query' => 'program']));
        $tagBuilder = $this->engine->lastSearchBuilder;

        $listResults = app(ScoutSearchBackend::class)->searchLists(request()->merge(['query' => 'article']));
        $listBuilder = $this->engine->lastSearchBuilder;

        $this->assertSame([$tag->id => 'Programming'], $tagResults->all());
        $this->assertSame('program', $tagBuilder->query);
        $this->assertContains(['field' => 'user_id', 'operator' => '=', 'value' => $user->id], $tagBuilder->wheres);

        $this->assertSame([$list->id => 'Articles'], $listResults->all());
        $this->assertSame('article', $listBuilder->query);
        $this->assertContains(['field' => 'user_id', 'operator' => '=', 'value' => $user->id], $listBuilder->wheres);
    }

    public function test_engine_exceptions_are_logged_and_converted(): void
    {
        $this->actingAs(User::factory()->create());
        config(['linkace.search.driver' => 'meilisearch']);
        $this->engine->exception = new RuntimeException('engine is down');

        Log::shouldReceive('error')
            ->once()
            ->withArgs(fn (string $message, array $context) => $message === 'External search failed.'
                && $context['driver'] === 'meilisearch'
                && $context['exception'] instanceof RuntimeException);

        $this->expectException(ExternalSearchUnavailableException::class);
        $this->expectExceptionMessage('The configured external search engine is currently unavailable.');

        app(ScoutSearchBackend::class)->searchLinks(new SearchQuery(
            query: 'example',
            searchTitle: false,
            searchDescription: false,
            visibility: null,
            brokenOnly: false,
            lists: [],
            tags: [],
            emptyLists: false,
            emptyTags: false,
            orderBy: null,
        ));
    }
}

class RecordingSearchEngine extends Engine
{
    public ?ScoutBuilder $lastPaginatedBuilder = null;
    public ?ScoutBuilder $lastSearchBuilder = null;
    public array $hitIds = [];
    public ?RuntimeException $exception = null;

    public function update($models): void
    {
    }

    public function delete($models): void
    {
    }

    public function search(ScoutBuilder $builder): array
    {
        if ($this->exception) {
            throw $this->exception;
        }

        $this->lastSearchBuilder = $builder;

        return ['hits' => $this->hits()];
    }

    public function paginate(ScoutBuilder $builder, $perPage, $page): array
    {
        if ($this->exception) {
            throw $this->exception;
        }

        $this->lastPaginatedBuilder = $builder;

        return [
            'hits' => $this->hits(),
            'total' => count($this->hitIds),
        ];
    }

    public function mapIds($results): \Illuminate\Support\Collection
    {
        return collect($results['hits'])->pluck('id')->values();
    }

    public function map(ScoutBuilder $builder, $results, $model): EloquentCollection
    {
        $ids = collect($results['hits'])->pluck('id')->all();

        if ($ids === []) {
            return $model->newCollection();
        }

        $query = $model->newQuery()->whereIn($model->getKeyName(), $ids);

        if ($builder->queryCallback) {
            ($builder->queryCallback)($query);
        }

        return $query->get()
            ->sortBy(fn ($model) => array_search($model->getKey(), $ids, true))
            ->values();
    }

    public function lazyMap(ScoutBuilder $builder, $results, $model): \Illuminate\Support\LazyCollection
    {
        return $this->map($builder, $results, $model)->lazy();
    }

    public function getTotalCount($results): int
    {
        return $results['total'] ?? count($results['hits']);
    }

    public function flush($model): void
    {
    }

    public function createIndex($name, array $options = []): void
    {
    }

    public function deleteIndex($name): void
    {
    }

    private function hits(): array
    {
        return collect($this->hitIds)
            ->map(fn (int $id) => ['id' => $id])
            ->all();
    }
}
