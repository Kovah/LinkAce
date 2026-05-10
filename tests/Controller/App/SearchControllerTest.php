<?php

namespace Tests\Controller\App;

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
        $this->post('search', [
            'query' => 'example',
        ])
            ->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_search_with_ordering(): void
    {
        $response = $this->post('search', [
            'query' => 'example',
            'order_by' => 'title:asc',
        ]);

        $body = $response->content();
        $posLink1 = strpos($body, 'https://empty-test.com');
        $posLink2 = strpos($body, 'https://example.com');

        $this->assertTrue($posLink1 < $posLink2);
    }

    public function test_valid_url_search_result(): void
    {
        $response = $this->post('search', [
            'query' => 'https://example.com',
        ]);

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_title_search_result(): void
    {
        $response = $this->post('search', [
            'query' => 'special',
            'search_title' => 'on',
        ]);

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_description_search_result(): void
    {
        $response = $this->post('search', [
            'query' => 'description',
            'search_description' => 'on',
        ]);

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_private_search_result(): void
    {
        $response = $this->post('search', [
            'query' => 'example',
            'private_only' => 'on',
        ]);

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_broken_search_result(): void
    {
        $response = $this->post('search', [
            'query' => 'broken',
            'broken_only' => 'on',
        ]);

        $response->assertOk()
            ->assertSee('https://broken.com')
            ->assertDontSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_tag_search_result(): void
    {
        $response = $this->post('search', [
            'only_lists' => '[]',
            'only_tags' => json_encode([1]),
        ]);

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_tag_search_result_without_result(): void
    {
        $response = $this->post('search', [
            'only_lists' => '[]',
            'only_tags' => json_encode([5]),
        ]);

        $response->assertOk()
            ->assertDontSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function test_valid_list_search_result(): void
    {
        $response = $this->post('search', [
            'only_lists' => json_encode([1]),
            'only_tags' => '[]',
        ]);

        $response->assertOk()
            ->assertSee('https://test.com')
            ->assertDontSee('https://example.com');
    }

    public function test_valid_list_search_result_without_results(): void
    {
        $response = $this->post('search', [
            'only_lists' => json_encode([5]),
            'only_tags' => '[]',
        ]);

        $response->assertOk()
            ->assertDontSee('https://test.com')
            ->assertDontSee('https://example.com');
    }

    public function test_empty_list_search_result(): void
    {
        $response = $this->post('search', [
            'query' => 'Test',
            'empty_lists' => 'on',
        ]);

        $response->assertOk()
            ->assertSee('https://empty-test.com')
            ->assertDontSee('https://test.com');
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
}
