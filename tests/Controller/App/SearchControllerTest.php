<?php

namespace Tests\Controller\App;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        config([
            'linkace.search.driver' => 'database',
            'scout.driver' => 'database',
        ]);

        $this->setupTestData();
    }

    public function test_valid_search_response(): void
    {
        $this->get('search')
            ->assertOk()
            ->assertSee('Search')
            ->assertSee('name="tag_mode"', false)
            ->assertSee('name="list_mode"', false)
            ->assertSee('value="all"', false)
            ->assertSee('name="exclude_tags"', false)
            ->assertSee('name="exclude_lists"', false);
    }

    public function test_valid_search_result(): void
    {
        $this->get('search?' . http_build_query([
            'query' => 'example',
        ]))
            ->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_search_with_ordering(): void
    {
        $response = $this->get('search?' . http_build_query([
            'query' => 'example',
            'order_by' => 'title:asc',
        ]));

        $body = $response->content();
        $posLink1 = strpos($body, 'https://empty-test.com');
        $posLink2 = strpos($body, 'https://example.com');

        $this->assertTrue($posLink1 < $posLink2);
    }

    public function test_valid_url_search_result(): void
    {
        $response = $this->get('search?' . http_build_query([
            'query' => 'https://example.com',
        ]));

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_title_search_result(): void
    {
        $response = $this->get('search?' . http_build_query([
            'query' => 'special',
            'search_title' => 'on',
        ]));

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_description_search_result(): void
    {
        $response = $this->get('search?' . http_build_query([
            'query' => 'description',
            'search_description' => 'on',
        ]));

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_private_search_result(): void
    {
        $response = $this->get('search?' . http_build_query([
            'query' => 'example',
            'private_only' => 'on',
        ]));

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_broken_search_result(): void
    {
        $response = $this->get('search?' . http_build_query([
            'query' => 'broken',
            'broken_only' => 'on',
        ]));

        $response->assertOk()
            ->assertSee('https://broken.com')
            ->assertDontSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_tag_search_result(): void
    {
        $response = $this->get('search?' . http_build_query([
            'only_lists' => '[]',
            'only_tags' => json_encode([1]),
        ]));

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_tag_search_result_without_result(): void
    {
        $response = $this->get('search?' . http_build_query([
            'only_lists' => '[]',
            'only_tags' => json_encode([5]),
        ]));

        $response->assertOk()
            ->assertDontSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_list_search_result(): void
    {
        $response = $this->get('search?' . http_build_query([
            'only_lists' => json_encode([1]),
            'only_tags' => '[]',
        ]));

        $response->assertOk()
            ->assertSee('https://test.com')
            ->assertDontSee('https://example.com');
    }

    public function test_valid_list_search_result_without_results(): void
    {
        $response = $this->get('search?' . http_build_query([
            'only_lists' => json_encode([5]),
            'only_tags' => '[]',
        ]));

        $response->assertOk()
            ->assertDontSee('https://test.com')
            ->assertDontSee('https://example.com');
    }

    public function test_list_filter_ignores_private_lists_of_other_users(): void
    {
        $list = $this->createPrivateTaxonomyOfOtherUser(LinkList::class);

        $this->get('search?' . http_build_query(['only_lists' => json_encode([$list->id])]))
            ->assertOk()
            ->assertDontSee('https://grouped.example')
            ->assertDontSee('https://ungrouped.example');

        $this->get('search?' . http_build_query(['only_lists' => json_encode([$list->id]), 'list_mode' => 'all']))
            ->assertOk()
            ->assertDontSee('https://grouped.example');

        $this->get('search?' . http_build_query(['query' => 'example', 'exclude_lists' => json_encode([$list->id])]))
            ->assertOk()
            ->assertSee('https://grouped.example')
            ->assertSee('https://ungrouped.example');
    }

    public function test_tag_filter_ignores_private_tags_of_other_users(): void
    {
        $tag = $this->createPrivateTaxonomyOfOtherUser(Tag::class);

        $this->get('search?' . http_build_query(['only_tags' => json_encode([$tag->id])]))
            ->assertOk()
            ->assertDontSee('https://grouped.example')
            ->assertDontSee('https://ungrouped.example');

        $this->get('search?' . http_build_query(['only_tags' => json_encode([$tag->id]), 'tag_mode' => 'all']))
            ->assertOk()
            ->assertDontSee('https://grouped.example');

        $this->get('search?' . http_build_query(['query' => 'example', 'exclude_tags' => json_encode([$tag->id])]))
            ->assertOk()
            ->assertSee('https://grouped.example')
            ->assertSee('https://ungrouped.example');
    }

    public function test_empty_list_search_result(): void
    {
        $response = $this->get('search?' . http_build_query([
            'query' => 'Test',
            'empty_lists' => 'on',
        ]));

        $response->assertOk()
            ->assertSee('https://empty-test.com')
            ->assertDontSee('https://test.com');
    }

    public function test_pagination_preserves_search_query(): void
    {
        $perPage = getPaginationLimit();
        for ($i = 0; $i < $perPage + 5; $i++) {
            Link::create([
                'user_id' => $this->user->id,
                'url' => "https://paginated-{$i}.example",
                'title' => "Findme Site {$i}",
            ]);
        }

        $response = $this->get('search?' . http_build_query([
            'query' => 'Findme',
            'search_title' => 'on',
        ]))
            ->assertOk();

        $response->assertSee('query=Findme', false);
        $response->assertSee('search_title=on', false);
        $response->assertSee('page=2', false);
    }

    protected function setupTestData(): void
    {
        $tagExample = Tag::create([
            'name' => 'Examples',
            'user_id' => $this->user->id,
        ]);

        $listTest = LinkList::create([
            'name' => 'A Tests List',
            'user_id' => $this->user->id,
        ]);

        $linkExample = Link::create([
            'user_id' => $this->user->id,
            'url' => 'https://example.com',
            'title' => 'Very special site title',
            'description' => 'Some description for this site',
            'is_private' => true,
        ]);

        $linkExample->tags()->attach($tagExample->id);

        $linkTest = Link::create([
            'user_id' => $this->user->id,
            'url' => 'https://test.com',
            'title' => 'Test Site',
            'description' => null,
            'is_private' => false,
        ]);

        $linkTest->lists()->attach($listTest->id);

        Link::create([
            'user_id' => $this->user->id,
            'url' => 'https://broken.com',
            'title' => 'Broken Site',
            'description' => 'Something must be broken here',
            'is_private' => false,
            'status' => Link::STATUS_BROKEN,
        ]);

        Link::create([
            'user_id' => $this->user->id,
            'url' => 'https://empty-test.com',
            'title' => 'Empty Test Site',
            'description' => null,
            'is_private' => false,
        ]);
    }

    /**
     * Creates a private list or tag of another user, which contains one of
     * two internal links of that user.
     */
    protected function createPrivateTaxonomyOfOtherUser(string $model): LinkList|Tag
    {
        $otherUser = User::factory()->create();

        $taxonomy = $model::factory()->create([
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $groupedLink = Link::factory()->create([
            'user_id' => $otherUser->id,
            'url' => 'https://grouped.example',
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);

        Link::factory()->create([
            'user_id' => $otherUser->id,
            'url' => 'https://ungrouped.example',
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);

        $model === LinkList::class
            ? $groupedLink->lists()->attach($taxonomy->id)
            : $groupedLink->tags()->attach($taxonomy->id);

        return $taxonomy;
    }
}
