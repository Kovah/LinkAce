<?php

namespace Tests\Controller;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function testTagsQuery(): void
    {
        Tag::factory()->create(['name' => 'testTag']); // must be found
        Tag::factory()->create(['name' => 'test*Tag']); // must be found
        Tag::factory()->create(['name' => 'blablaTag']); // must not be found

        $response = $this->post('fetch/tags', [
            'query' => 'test',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                ['text' => 'test*Tag'],
                ['text' => 'testTag'],
            ])
            ->assertJsonMissing(['text' => 'blablaTag']);
    }

    public function testListsQuery(): void
    {
        LinkList::factory()->create(['name' => 'testList']); // must be found
        LinkList::factory()->create(['name' => 'test*List']); // must be found
        LinkList::factory()->create(['name' => 'blablaList']); // must not be found

        $response = $this->post('fetch/lists', [
            'query' => 'test',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                ['text' => 'test*List'],
                ['text' => 'testList'],
            ])
            ->assertJsonMissing(['text' => 'blablaList']);
    }

    public function testExistingUrlSearch(): void
    {
        Link::factory()->create(['url' => 'https://duckduckgo.com']);

        $response = $this->post('fetch/existing-links', [
            'query' => 'https://duckduckgo.com',
        ]);

        $response->assertOk()->assertJson(['linkFound' => true]);
    }

    public function testExistingUrlSearchWithoutResult(): void
    {
        Link::factory()->create(['url' => 'https://duckduckgo.com']);

        $response = $this->post('fetch/existing-links', [
            'query' => 'https://microsoft.com',
        ]);

        $response->assertOk()->assertJson(['linkFound' => false]);
    }

    public function testGetHtmlForUrl(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '</head></html>';

        Http::fake([
            'example.com' => Http::response($testHtml, 200),
        ]);

        $response = $this->post('fetch/html-for-url', [
            'url' => 'https://example.com',
        ]);

        $response->assertOk();

        $responseHtml = $response->content();
        $this->assertEquals($testHtml, $responseHtml);
    }

    public function testGetHtmlForInvalidUrl(): void
    {
        $response = $this->post('fetch/html-for-url', [
            'url' => 'not a url',
        ]);

        $response->assertSessionHasErrors('url');
    }

    public function testGetHtmlForUrlWithFailure(): void
    {
        Http::fake([
            'example.com' => Http::response('', 500),
        ]);

        $response = $this->post('fetch/html-for-url', [
            'url' => 'https://example.com',
        ]);

        $response->assertOk()->assertSee('');
    }
}
