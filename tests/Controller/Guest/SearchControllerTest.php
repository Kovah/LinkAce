<?php

namespace Tests\Controller\Guest;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SystemSettings::fake([
            'guest_access_enabled' => true,
            'setup_completed' => true,
        ]);

        User::factory()->create();
    }

    public function test_search_form_is_reachable(): void
    {
        $this->get('guest/search')
            ->assertOk()
            ->assertSee('Search');
    }

    public function test_search_redirects_to_login_when_guest_access_disabled(): void
    {
        SystemSettings::fake(['guest_access_enabled' => false, 'setup_completed' => true]);

        $this->get('guest/search')->assertRedirect('login');
    }

    public function test_search_returns_only_public_links(): void
    {
        Link::factory()->create([
            'url' => 'https://public.example',
            'title' => 'Findme Public',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        Link::factory()->create([
            'url' => 'https://internal.example',
            'title' => 'Findme Internal',
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);
        Link::factory()->create([
            'url' => 'https://private.example',
            'title' => 'Findme Private',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $this->get('guest/search?query=Findme&search_title=on')
            ->assertOk()
            ->assertSee('https://public.example')
            ->assertDontSee('https://internal.example')
            ->assertDontSee('https://private.example');
    }

    public function test_search_does_not_expose_private_tags_on_public_link(): void
    {
        $publicTag = Tag::factory()->create([
            'name' => 'GuestVisibleTag',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $privateTag = Tag::factory()->create([
            'name' => 'GuestHiddenTag',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $link = Link::factory()->create([
            'url' => 'https://tagged.example',
            'title' => 'Tagged Site',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $link->tags()->sync([$publicTag->id, $privateTag->id]);

        $this->get('guest/search?query=Tagged&search_title=on')
            ->assertOk()
            ->assertSee('GuestVisibleTag')
            ->assertDontSee('GuestHiddenTag');
    }

    public function test_search_form_only_lists_public_tags_and_lists(): void
    {
        Tag::factory()->create([
            'name' => 'GuestVisibleTag',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        Tag::factory()->create([
            'name' => 'GuestHiddenTag',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);
        LinkList::factory()->create([
            'name' => 'GuestVisibleList',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        LinkList::factory()->create([
            'name' => 'GuestHiddenList',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $this->get('guest/search')
            ->assertOk()
            ->assertSee('GuestVisibleTag')
            ->assertDontSee('GuestHiddenTag')
            ->assertSee('GuestVisibleList')
            ->assertDontSee('GuestHiddenList');
    }

    public function test_search_does_not_match_via_private_tag_id(): void
    {
        $privateTag = Tag::factory()->create([
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);
        $link = Link::factory()->create([
            'url' => 'https://leak-via-tag.example',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $link->tags()->sync([$privateTag->id]);

        $this->get('guest/search?only_tags=' . $privateTag->id)
            ->assertOk()
            ->assertDontSee('https://leak-via-tag.example');
    }

    public function test_search_does_not_match_via_private_list_id(): void
    {
        $privateList = LinkList::factory()->create([
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);
        $link = Link::factory()->create([
            'url' => 'https://leak-via-list.example',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        $link->lists()->sync([$privateList->id]);

        $this->get('guest/search?only_lists=' . $privateList->id)
            ->assertOk()
            ->assertDontSee('https://leak-via-list.example');
    }

    public function test_search_rejects_admin_only_filters(): void
    {
        $this->get('guest/search?query=foo&broken_only=on')
            ->assertSessionHasErrors('broken_only');

        $this->get('guest/search?query=foo&visibility=' . ModelAttribute::VISIBILITY_PRIVATE)
            ->assertSessionHasErrors('visibility');

        $this->get('guest/search?query=foo&empty_tags=on')
            ->assertSessionHasErrors('empty_tags');

        $this->get('guest/search?query=foo&empty_lists=on')
            ->assertSessionHasErrors('empty_lists');
    }

    public function test_search_rejects_array_inputs_for_relation_filters(): void
    {
        $this->get('guest/search?only_tags[]=1&only_tags[]=2')
            ->assertSessionHasErrors('only_tags');

        $this->get('guest/search?only_lists[]=1')
            ->assertSessionHasErrors('only_lists');
    }

    public function test_pagination_preserves_search_query(): void
    {
        $perPage = getPaginationLimit();
        for ($i = 0; $i < $perPage + 5; $i++) {
            Link::factory()->create([
                'url' => "https://paginated-{$i}.example",
                'title' => "Findme Site {$i}",
                'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
            ]);
        }

        $response = $this->get('guest/search?query=Findme&search_title=on')
            ->assertOk();

        $response->assertSee('query=Findme', false);
        $response->assertSee('search_title=on', false);
        $response->assertSee('page=2', false);
    }
}
