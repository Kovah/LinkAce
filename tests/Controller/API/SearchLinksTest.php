<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchLinksTest extends ApiTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'linkace.search.driver' => 'database',
            'scout.driver' => 'database',
        ]);
    }

    public function test_unauthorized_request(): void
    {
        $this->getJson('api/v2/search/links')->assertUnauthorized();
    }

    public function test_without_query(): void
    {
        $msg = 'You must either enter a search query, or select a list, a tag or enable searching for broken links.';
        $this->getJsonAuthorized('api/v2/search/links')
            ->assertJsonValidationErrors([
            'query' => $msg,
            'only_lists' => $msg,
            'only_tags' => $msg,
            'broken_only' => $msg,
        ]);
    }

    public function test_regular_search_result(): void
    {
        $link = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://example.com',
        ]);

        $link2 = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://another-example.org',
        ]);

        // This link must not be present in the results
        $excludedLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://test.com',
        ]);

        $url = sprintf('api/v2/search/links?query=%s', 'example');
        $this->getJsonAuthorized($url)
            ->assertOk()
            ->assertJsonFragment([
                'current_page' => 1,
            ])
            ->assertJsonFragment([
                'url' => $link->url,
            ])
            ->assertJsonFragment([
                'url' => $link2->url,
            ])
            ->assertJsonMissing([
                'url' => $excludedLink->url,
            ]);
    }

    public function test_search_by_title(): void
    {
        $link = Link::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Test Title',
        ]);

        // This link must not be present in the results
        $excludedLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Nobody cares',
        ]);

        $url = sprintf('api/v2/search/links?query=%s&search_title=1', 'Test');
        $this->getJsonAuthorized($url)
            ->assertOk()
            ->assertJsonFragment([
                'url' => $link->url,
            ])
            ->assertJsonMissing([
                'url' => $excludedLink->url,
            ]);
    }

    public function test_search_by_description(): void
    {
        $link = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://test.com',
            'description' => 'Example description',
        ]);

        // This link must not be present in the results
        $excludedLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://test.org',
            'description' => 'Lorem Ipsum',
        ]);

        $url = sprintf('api/v2/search/links?query=%s&search_description=1', 'Example');
        $this->getJsonAuthorized($url)
            ->assertOk()
            ->assertJsonFragment([
                'url' => $link->url,
            ])
            ->assertJsonMissing([
                'url' => $excludedLink->url,
            ]);
    }

    public function test_search_private_only(): void
    {
        $link = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://test.com',
            'visibility' => 1,
        ]);

        // This link must not be present in the results
        $excludedLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://test.org',
            'visibility' => 3,
        ]);

        $url = sprintf('api/v2/search/links?query=%s&visibility=1', 'test');
        $this->getJsonAuthorized($url)
            ->assertOk()
            ->assertJsonFragment([
                'url' => $link->url,
            ])
            ->assertJsonMissing([
                'url' => $excludedLink->url,
            ]);
    }

    public function test_search_broken_only(): void
    {
        $link = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://test.com',
            'status' => Link::STATUS_BROKEN,
        ]);

        // This link must not be present in the results
        $excludedLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://test.org',
        ]);

        $url = sprintf('api/v2/search/links?query=%s&broken_only=1', 'test');
        $this->getJsonAuthorized($url)
            ->assertOk()
            ->assertJsonFragment([
                'url' => $link->url,
            ])
            ->assertJsonMissing([
                'url' => $excludedLink->url,
            ]);
    }

    public function test_search_with_lists(): void
    {
        $list = LinkList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Scientific Articles',
        ]);

        $link = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://test.com',
        ]);

        $link->lists()->sync([$list->id]);

        // This link must not be present in the results
        $excludedLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://test.org',
        ]);

        $url = sprintf('api/v2/search/links?only_lists=%s', $list->id);
        $this->getJsonAuthorized($url)
            ->assertOk()
            ->assertJsonFragment([
                'url' => $link->url,
            ])
            ->assertJsonMissing([
                'url' => $excludedLink->url,
            ]);
    }

    public function test_search_with_tags(): void
    {
        $tag = Tag::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'artificial-intelligence',
        ]);

        $link = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://test.com',
        ]);

        $link->tags()->sync([$tag->id]);

        // This link must not be present in the results
        $excludedLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://test.org',
        ]);

        $url = sprintf('api/v2/search/links?only_tags=%s', $tag->id);
        $this->getJsonAuthorized($url)
            ->assertOk()
            ->assertJsonFragment([
                'url' => $link->url,
            ])
            ->assertJsonMissing([
                'url' => $excludedLink->url,
            ]);
    }

    public function test_search_with_multiple_tags_remains_any_mode_by_default(): void
    {
        [$recipes, $vegetarian] = Tag::factory()
            ->count(2)
            ->create(['user_id' => $this->user->id]);

        $recipesLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://recipes.example',
        ]);
        $recipesLink->tags()->sync([$recipes->id]);

        $vegetarianLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://vegetarian.example',
        ]);
        $vegetarianLink->tags()->sync([$vegetarian->id]);

        $url = sprintf('api/v2/search/links?only_tags=%s,%s', $recipes->id, $vegetarian->id);
        $this->getJsonAuthorized($url)
            ->assertOk()
            ->assertJsonFragment(['url' => $recipesLink->url])
            ->assertJsonFragment(['url' => $vegetarianLink->url]);
    }

    public function test_search_with_all_tag_mode_requires_every_selected_tag(): void
    {
        [$recipes, $vegetarian] = Tag::factory()
            ->count(2)
            ->create(['user_id' => $this->user->id]);

        $matchingLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://vegetarian-recipes.example',
        ]);
        $matchingLink->tags()->sync([$recipes->id, $vegetarian->id]);

        $recipesOnlyLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://recipes-only.example',
        ]);
        $recipesOnlyLink->tags()->sync([$recipes->id]);

        $url = sprintf('api/v2/search/links?only_tags=%s,%s&tag_mode=all', $recipes->id, $vegetarian->id);
        $this->getJsonAuthorized($url)
            ->assertOk()
            ->assertJsonFragment(['url' => $matchingLink->url])
            ->assertJsonMissing(['url' => $recipesOnlyLink->url]);
    }

    public function test_search_can_exclude_tags_and_lists(): void
    {
        $includeTag = Tag::factory()->create(['user_id' => $this->user->id]);
        $excludeTag = Tag::factory()->create(['user_id' => $this->user->id]);
        $excludeList = LinkList::factory()->create(['user_id' => $this->user->id]);

        $matchingLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://allowed.example',
        ]);
        $matchingLink->tags()->sync([$includeTag->id]);

        $taggedOutLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://excluded-tag.example',
        ]);
        $taggedOutLink->tags()->sync([$includeTag->id, $excludeTag->id]);

        $listedOutLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://excluded-list.example',
        ]);
        $listedOutLink->tags()->sync([$includeTag->id]);
        $listedOutLink->lists()->sync([$excludeList->id]);

        $url = sprintf(
            'api/v2/search/links?only_tags=%s&exclude_tags=%s&exclude_lists=%s',
            $includeTag->id,
            $excludeTag->id,
            $excludeList->id
        );
        $this->getJsonAuthorized($url)
            ->assertOk()
            ->assertJsonFragment(['url' => $matchingLink->url])
            ->assertJsonMissing(['url' => $taggedOutLink->url])
            ->assertJsonMissing(['url' => $listedOutLink->url]);
    }

    public function test_search_with_all_list_mode_requires_every_selected_list(): void
    {
        [$articles, $research] = LinkList::factory()
            ->count(2)
            ->create(['user_id' => $this->user->id]);

        $matchingLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://research-articles.example',
        ]);
        $matchingLink->lists()->sync([$articles->id, $research->id]);

        $articlesOnlyLink = Link::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://articles-only.example',
        ]);
        $articlesOnlyLink->lists()->sync([$articles->id]);

        $url = sprintf('api/v2/search/links?only_lists=%s,%s&list_mode=all', $articles->id, $research->id);
        $this->getJsonAuthorized($url)
            ->assertOk()
            ->assertJsonFragment(['url' => $matchingLink->url])
            ->assertJsonMissing(['url' => $articlesOnlyLink->url]);
    }
}
