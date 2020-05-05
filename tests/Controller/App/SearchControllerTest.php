<?php

namespace Tests\Database;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);

        $this->setupTestData();
    }

    public function testValidSearchResponse(): void
    {
        $response = $this->get('search');

        $response->assertStatus(200)
            ->assertSee('Search');
    }

    public function testValidSearchResult(): void
    {
        $response = $this->post('search', [
            'query' => 'example',
        ]);

        $response->assertStatus(200)
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function testValidUrlSearchResult(): void
    {
        $response = $this->post('search', [
            'query' => 'https://example.com',
        ]);

        $response->assertStatus(200)
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function testValidTitleSearchResult(): void
    {
        $response = $this->post('search', [
            'query' => 'special',
            'search_title' => 'on',
        ]);

        $response->assertStatus(200)
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function testValidDescriptionSearchResult(): void
    {
        $response = $this->post('search', [
            'query' => 'description',
            'search_description' => 'on',
        ]);

        $response->assertStatus(200)
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function testValidPrivateSearchResult(): void
    {
        $response = $this->post('search', [
            'query' => 'example',
            'private_only' => 'on',
        ]);

        $response->assertStatus(200)
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function testValidBrokenSearchResult(): void
    {
        $response = $this->post('search', [
            'query' => 'broken',
            'broken_only' => 'on',
        ]);

        $response->assertStatus(200)
            ->assertSee('https://broken.com')
            ->assertDontSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function testValidTagSearchResult(): void
    {
        $response = $this->post('search', [
            'only_tags' => 'Examples',
        ]);

        $response->assertStatus(200)
            ->assertSee('https://example.com')
            ->assertDontSee('https://test.com');
    }

    public function testValidListSearchResult(): void
    {
        $response = $this->post('search', [
            'only_lists' => 'A Tests List',
        ]);

        $response->assertStatus(200)
            ->assertSee('https://test.com')
            ->assertDontSee('https://example.com');
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

        $linkBroken = Link::create([
            'user_id' => $this->user->id,
            'url' => 'https://broken.com',
            'title' => 'Broken Site',
            'description' => 'Something must be broken here',
            'is_private' => false,
            'status' => Link::STATUS_BROKEN,
        ]);
    }
}
