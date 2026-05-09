<?php

namespace Tests\Search\External;

use App\Enums\ApiToken;
use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Meilisearch\Client as MeilisearchClient;
use Meilisearch\Exceptions\ApiException;
use Tests\TestCase;

abstract class ExternalSearchTestCase extends TestCase
{
    use RefreshDatabase;

    private static ?bool $meilisearchIsHealthy = null;

    protected ?string $searchIndexPrefix = null;
    protected User $user;
    protected string $accessToken;

    abstract protected function driver(): string;

    protected function setUp(): void
    {
        parent::setUp();

        $this->skipWhenEngineIsNotConfigured();
        $this->searchIndexPrefix = $this->makeSearchIndexPrefix();

        config([
            'linkace.search.driver' => $this->driver(),
            'scout.driver' => $this->driver(),
            'scout.prefix' => $this->searchIndexPrefix,
            'scout.queue' => false,
        ]);

        $this->waitForEngine();

        $this->user = User::factory()->create();
        $this->accessToken = $this->user
            ->createToken('external-search-test', [ApiToken::ABILITY_USER_ACCESS])
            ->plainTextToken;

        $this->artisan('linkace:search:setup')->assertSuccessful();
    }

    protected function tearDown(): void
    {
        try {
            $this->cleanupExternalSearchIndexes();
        } finally {
            parent::tearDown();
        }
    }

    protected function skipWhenEngineIsNotConfigured(): void
    {
    }

    protected function waitForEngine(): void
    {
    }

    protected function cleanupExternalSearchIndexes(): void
    {
    }

    public function test_external_engine_matches_terms_with_typos_splits_and_diacritics(): void
    {
        $this->createSearchFixtures();
        $this->rebuildSearchIndex();

        $this->assertSearchContains('flower', 'https://flowers.example.com');
        $this->assertSearchContains('budha pest', 'https://budapest.example.com');
        $this->assertSearchContains('buda pest', 'https://budapest.example.com');
        $this->assertSearchContains('cafe', 'https://cafe.example.com', ['search_title' => 1]);
        $this->assertSearchContains('cafe', 'https://accent.example.com', ['search_title' => 1]);
    }

    public function test_title_and_description_search_can_be_toggled(): void
    {
        $titleLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://title-field.example.com',
            'title' => 'titleonlymarker',
            'description' => 'unrelated',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $descriptionLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://description-field.example.com',
            'title' => 'unrelated',
            'description' => 'descriptiononlymarker',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $this->rebuildSearchIndex();

        $this->assertSearchContains('titleonlymarker', $titleLink->url, ['search_title' => 1]);
        $this->assertSearchMissing('descriptiononlymarker', $descriptionLink->url, ['search_title' => 1]);

        $this->assertSearchContains('descriptiononlymarker', $descriptionLink->url, ['search_description' => 1]);
        $this->assertSearchMissing('titleonlymarker', $titleLink->url, ['search_description' => 1]);
    }

    public function test_private_links_from_other_users_are_never_returned(): void
    {
        $otherUser = User::factory()->create();
        $visibleLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://visible-secret.example.com',
            'title' => 'Shared Secret Needle',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $hiddenLink = Link::factory()->create([
            'user_id' => $otherUser->id,
            'url' => 'https://hidden-secret.example.com',
            'title' => 'Shared Secret Needle',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);
        $this->rebuildSearchIndex();

        $this->assertSearchContains('Shared Secret Needle', $visibleLink->url, ['search_title' => 1]);
        $this->assertSearchMissing('Shared Secret Needle', $hiddenLink->url, ['search_title' => 1]);
    }

    protected function createSearchFixtures(): void
    {
        Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://flowers.example.com',
            'title' => 'Flowers',
            'description' => 'A plural flower example',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://budapest.example.com',
            'title' => 'Budapest Travel Notes',
            'description' => 'Buda Pest city guide',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://cafe.example.com',
            'title' => 'Cafe Guide',
            'description' => 'Coffee without accents',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://accent.example.com',
            'title' => 'Café Guide',
            'description' => 'Coffee with accents',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
    }

    protected function rebuildSearchIndex(): void
    {
        $this->artisan('linkace:search:rebuild')->assertSuccessful();
    }

    protected function assertSearchContains(string $query, string $url, array $parameters = []): void
    {
        $this->waitForSearch($query, $parameters, fn (array $urls) => in_array($url, $urls, true));
        $this->assertContains($url, $this->searchUrls($query, $parameters));
    }

    protected function assertSearchMissing(string $query, string $url, array $parameters = []): void
    {
        $this->waitForSearch($query, $parameters, fn (array $urls) => ! in_array($url, $urls, true));
        $this->assertNotContains($url, $this->searchUrls($query, $parameters));
    }

    protected function waitForSearch(string $query, array $parameters, callable $condition): void
    {
        $deadline = microtime(true) + 5;

        do {
            if ($condition($this->searchUrls($query, $parameters))) {
                return;
            }

            usleep(250_000);
        } while (microtime(true) < $deadline);
    }

    protected function searchUrls(string $query, array $parameters = []): array
    {
        $response = $this->getJson('api/v2/search/links?'.http_build_query(array_merge([
            'query' => $query,
        ], $parameters)), [
            'Authorization' => 'Bearer '.$this->accessToken,
        ]);

        $response->assertOk();

        return collect($response->json('data'))->pluck('url')->all();
    }

    protected function waitForMeilisearch(): void
    {
        if (self::$meilisearchIsHealthy === false) {
            $this->markTestSkipped('Meilisearch is not reachable.');
        }

        if (self::$meilisearchIsHealthy === true) {
            return;
        }

        $deadline = microtime(true) + 10;
        $client = app(MeilisearchClient::class);

        do {
            if ($client->isHealthy()) {
                self::$meilisearchIsHealthy = true;

                return;
            }

            usleep(250_000);
        } while (microtime(true) < $deadline);

        self::$meilisearchIsHealthy = false;

        if (! env('CI')) {
            $this->markTestSkipped('Meilisearch is not reachable.');
        }

        $this->fail('Meilisearch did not become healthy before the external search tests started.');
    }

    protected function cleanupMeilisearchIndexes(): void
    {
        if ($this->searchIndexPrefix === null || self::$meilisearchIsHealthy !== true) {
            return;
        }

        $client = app(MeilisearchClient::class);
        $taskIds = [];

        foreach ($this->searchIndexNames() as $indexName) {
            try {
                $task = $client->deleteIndex($this->searchIndexPrefix.$indexName);
                $taskIds[] = $task['taskUid'];
            } catch (ApiException $exception) {
                if ($exception->httpStatus !== 404) {
                    throw $exception;
                }
            }
        }

        if ($taskIds !== []) {
            $client->waitForTasks($taskIds, 5000, 50);
        }
    }

    protected function makeSearchIndexPrefix(): string
    {
        return 'linkace_external_test_'.getmypid().'_'.$this->name().'_';
    }

    protected function searchIndexNames(): array
    {
        return [
            'linkace_links',
            'linkace_tags',
            'linkace_lists',
        ];
    }
}
