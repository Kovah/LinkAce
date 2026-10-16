<?php

namespace Tests\Controller\App;

use App\Enums\ApiToken;
use App\Enums\ModelAttribute;
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

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_unauthorized_request(): void
    {
        $response = $this->get('links/feed');

        $response->assertRedirect('login');
    }

    public function test_links_feed(): void
    {
        $link = Link::factory()->create([
            'description' => ']]><svg xmlns="http://www.w3.org/2000/svg" onload="alert(document.documentURI)"/><![CDATA[',
        ]);

        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->getAuthorized('links/feed')
            ->assertOk()
            ->assertSee($link->url)
            ->assertSee(
                '&amp;lt;svg xmlns=&amp;quot;http://www.w3.org/2000/svg&amp;quot; onload=&amp;quot;alert(document.documentURI)&amp;quot;/&amp;gt;',
                false
            )
            ->assertDontSee(
                ']]><svg xmlns="http://www.w3.org/2000/svg" onload="alert(document.documentURI)"/><![CDATA[',
                false
            )
            ->assertDontSee($otherLink->url);
    }

    public function test_links_feed_with_auth_as_query_param(): void
    {
        $link = Link::factory()->create();

        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $token = $this->user->createToken('test', [ApiToken::ABILITY_USER_ACCESS])->plainTextToken;
        $response = $this->get('links/feed?api_token=' . $token);

        $response->assertOk()->assertSee($link->url)->assertDontSee($otherLink->url);
    }

    public function test_lists_feed(): void
    {
        $list = LinkList::factory()->create([
            'description' => ']]><svg xmlns="http://www.w3.org/2000/svg" onload="alert(document.documentURI)"/><![CDATA[',
        ]);

        $otherUser = User::factory()->create();
        $otherTList = LinkList::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $this->getAuthorized('lists/feed')
            ->assertOk()
            ->assertSee($list->name)
            ->assertSee(
                '&amp;lt;svg xmlns=&amp;quot;http://www.w3.org/2000/svg&amp;quot; onload=&amp;quot;alert(document.documentURI)&amp;quot;/&amp;gt;',
                false
            )
            ->assertDontSee(
                ']]><svg xmlns="http://www.w3.org/2000/svg" onload="alert(document.documentURI)"/><![CDATA[',
                false
            )
            ->assertDontSee($otherTList->name);
    }

    public function test_list_link_feed(): void
    {
        $link = LinkList::factory()->create();
        $listLink = Link::factory()->create();
        $unrelatedLink = Link::factory()->create();

        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $listLink->lists()->sync([$link->id, $otherLink->id]);

        $response = $this->getAuthorized('lists/1/feed');

        $response->assertOk()
            ->assertSee($link->name)
            ->assertSee($listLink->url)
            ->assertDontSee($otherLink->url)
            ->assertDontSee($unrelatedLink->url);
    }

    public function test_private_list_link_feed_from_other_user_is_forbidden(): void
    {
        $otherUser = User::factory()->create();
        $privateList = LinkList::factory()->for($otherUser)->create([
            'name' => 'secret list',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $response = $this->getAuthorized('lists/' . $privateList->id . '/feed');

        $response->assertForbidden()
            ->assertDontSee('secret list');
    }

    public function test_tags_feed(): void
    {
        $tag = Tag::factory()->create();

        $otherUser = User::factory()->create();
        $otherTag = Tag::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $response = $this->getAuthorized('tags/feed');

        $response->assertOk()->assertSee($tag->name)->assertDontSee($otherTag->name);
    }

    public function test_tag_link_feed(): void
    {
        $tag = Tag::factory()->create();
        $tagLink = Link::factory()->create();
        $unrelatedLink = Link::factory()->create();

        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $tagLink->tags()->sync([$tag->id, $otherLink->id]);

        $response = $this->getAuthorized('tags/1/feed');

        $response->assertOk()
            ->assertSee($tag->name)
            ->assertSee($tagLink->url)
            ->assertDontSee($otherLink->url)
            ->assertDontSee($unrelatedLink->url);
    }

    public function test_private_tag_link_feed_from_other_user_is_forbidden(): void
    {
        $otherUser = User::factory()->create();
        $privateTag = Tag::factory()->for($otherUser)->create([
            'name' => 'secret tag',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $response = $this->getAuthorized('tags/' . $privateTag->id . '/feed');

        $response->assertForbidden()
            ->assertDontSee('secret tag');
    }

    public function test_feeds_are_forbidden_for_system_tokens(): void
    {
        $link = Link::factory()->create();
        $list = LinkList::factory()->create();
        $tag = Tag::factory()->create();
        $link->lists()->sync([$list->id]);
        $link->tags()->sync([$tag->id]);

        // System tokens hold the links/lists/tags read abilities, but the feeds are
        // web routes and the system user is locked out of those entirely.
        $abilities = [
            ApiToken::ABILITY_LINKS_READ,
            ApiToken::ABILITY_LISTS_READ,
            ApiToken::ABILITY_TAGS_READ,
        ];

        foreach ([
            'links/feed',
            'lists/feed',
            'lists/' . $list->id . '/feed',
            'tags/feed',
            'tags/' . $tag->id . '/feed',
        ] as $uri) {
            $this->getAuthorizedAsSystem($uri, $abilities)
                ->assertForbidden()
                ->assertDontSee($link->url);
        }
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
        $token = $this->user->createToken('test', [ApiToken::ABILITY_USER_ACCESS])->plainTextToken;
        $headers['Authorization'] = 'Bearer ' . $token;
        return $this->get($uri, $headers);
    }

    /**
     * Send a request authorized with a system token carrying the given abilities.
     *
     * @param string $uri
     * @param array  $abilities
     * @return TestResponse
     */
    public function getAuthorizedAsSystem(string $uri, array $abilities = []): TestResponse
    {
        $abilities[] = ApiToken::ABILITY_SYSTEM_ACCESS;
        $token = User::getSystemUser()->createToken('test', $abilities)->plainTextToken;

        return $this->get($uri, ['Authorization' => 'Bearer ' . $token]);
    }
}
