<?php

namespace Tests\Controller;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Kovah\HtmlMeta\Exceptions\DisallowedIpException;
use Kovah\HtmlMeta\Facades\HtmlMeta;
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

    public function test_tags_query(): void
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
                ['name' => 'test*Tag'],
                ['name' => 'testTag'],
            ])
            ->assertJsonMissing(['text' => 'blablaTag']);
    }

    public function test_lists_query(): void
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
                ['name' => 'test*List'],
                ['name' => 'testList'],
            ])
            ->assertJsonMissing(['text' => 'blablaList']);
    }

    public function test_existing_url_search(): void
    {
        Link::factory()->create(['url' => 'https://duckduckgo.com']);

        $response = $this->post('fetch/existing-links', [
            'query' => 'https://duckduckgo.com',
        ]);

        $response->assertOk()->assertJson(['linkFound' => true]);
    }

    public function test_existing_url_search_without_result(): void
    {
        Link::factory()->create(['url' => 'https://duckduckgo.com']);

        $response = $this->post('fetch/existing-links', [
            'query' => 'https://microsoft.com',
        ]);

        $response->assertOk()->assertJson(['linkFound' => false]);
    }

    public function test_get_html_keywords_for_url(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '<meta name="keywords" content="html, css, javascript">' .
            '</head></html>';

        Http::fake([
            'example.com' => Http::response($testHtml, 200),
        ]);

        $response = $this->post('fetch/keywords-for-url', [
            'url' => 'https://example.com',
        ]);

        $response->assertOk();

        $keywords = $response->json('keywords');
        $this->assertEquals('html', $keywords[0]);
        $this->assertEquals('css', $keywords[1]);
        $this->assertEquals('javascript', $keywords[2]);
    }

    public function test_get_keywords_for_invalid_url(): void
    {
        $response = $this->post('fetch/keywords-for-url', [
            'url' => 'not a url',
        ]);

        $response->assertSessionHasErrors('url');
    }

    public function test_get_keywords_for_private_ip_url(): void
    {
        $response = $this->post('fetch/keywords-for-url', [
            'url' => 'http://192.168.0.126/admin',
        ]);

        $response->assertOk()->assertJson(['keywords' => null]);
    }

    public function test_get_keywords_for_hostname_resolving_to_private_ip_url(): void
    {
        HtmlMeta::shouldReceive('forUrl')
            ->once()
            ->with('http://internal-service')
            ->andThrow(new DisallowedIpException('http://internal-service resolves to a non-public IP address.'));

        $response = $this->post('fetch/keywords-for-url', [
            'url' => 'http://internal-service',
        ]);

        $response->assertOk()->assertJson(['keywords' => null]);
    }

    public function test_get_keywords_for_public_ip_url(): void
    {
        $testHtml = '<!DOCTYPE html><head>' .
            '<title>Example Title</title>' .
            '<meta name="description" content="This an example description">' .
            '<meta name="keywords" content="html, css, javascript">' .
            '</head></html>';

        Http::fake([
            '104.102.37.33' => Http::response($testHtml, 200),
        ]);

        $response = $this->post('fetch/keywords-for-url', [
            'url' => 'http://104.102.37.33/research/cold_fusion.html',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_get_keywords_for_url_with_failure(): void
    {
        Http::fake([
            'example.com' => Http::response('', 500),
        ]);

        $response = $this->post('fetch/keywords-for-url', [
            'url' => 'https://example.com',
        ]);

        $response->assertOk()->assertJson(['keywords' => null]);
    }
}
