<?php

namespace Tests\Controller\App;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class FeedControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testUnauthorizedRequest(): void
    {
        $response = $this->get('links/feed');

        $response->assertRedirect('login');
    }

    public function testLinksFeed(): void
    {
        $link = Link::factory()->create();

        $response = $this->getAuthorized('links/feed');

        $response->assertOk()
            ->assertSee($link->url);
    }

    public function testListsFeed(): void
    {
        $list = LinkList::factory()->create();

        $response = $this->getAuthorized('lists/feed');

        $response->assertOk()
            ->assertSee($list->name);
    }

    public function testTagsFeed(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->getAuthorized('tags/feed');

        $response->assertOk()
            ->assertSee($tag->name);
    }

    /**
     * Send an authorized request for the GET method.
     *
     * @param string $uri
     * @param array  $headers
     * @return TestResponse
     */
    public function getAuthorized(string $uri, array $headers = []): TestResponse
    {
        $headers['Authorization'] = 'Bearer ' . $this->user->api_token;
        return $this->get($uri, $headers);
    }
}
