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
        $this->post('guest/search', ['query' => 'foo'])->assertRedirect('login');
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

        $this->post('guest/search', ['query' => 'Findme', 'search_title' => 'on'])
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

        $this->post('guest/search', ['query' => 'Tagged', 'search_title' => 'on'])
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

    public function test_search_rejects_admin_only_filters(): void
    {
        $this->post('guest/search', [
            'query' => 'foo',
            'broken_only' => 'on',
        ])->assertSessionHasErrors('broken_only');

        $this->post('guest/search', [
            'query' => 'foo',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ])->assertSessionHasErrors('visibility');

        $this->post('guest/search', [
            'query' => 'foo',
            'empty_tags' => 'on',
        ])->assertSessionHasErrors('empty_tags');

        $this->post('guest/search', [
            'query' => 'foo',
            'empty_lists' => 'on',
        ])->assertSessionHasErrors('empty_lists');
    }
}
