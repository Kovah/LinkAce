<?php

namespace Tests\Models;

use App\Audits\Modifiers\ListRelationModifier;
use App\Audits\Modifiers\TagRelationModifier;
use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression tests for GHSA-538m-p86m-c8jg: tag and list relations are loaded
 * and resolved without the visibleForUser() scope, so another user's private
 * tag or list name can be rendered to a viewer who must not see it. The
 * advisory names the HTML export as the proven sink; the reports closed as
 * duplicates of it (GHSA-5xgq-845c-v5cc, GHSA-2qr6-w2wp-6gp7,
 * GHSA-v7cf-v3pq-6w35) name the link listing, the link form taxonomy lookup
 * and the audit history as further sinks of the same root cause.
 *
 * Unlike the export sink, the listing sink needs no pre-existing cross-user
 * pivot: a user can tag their own *public* link with their own *private* tag,
 * and every other user then sees that tag name in their link listing.
 */
class PrivateTaxonomyLeakTest extends TestCase
{
    use RefreshDatabase;

    private User $viewer;
    private User $owner;
    private Tag $privateTag;
    private LinkList $privateList;
    private Link $publicLink;

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewer = User::factory()->create();
        $this->owner = User::factory()->create();

        $this->privateTag = Tag::factory()->for($this->owner)->create([
            'name' => 'owners-private-tag',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $this->privateList = LinkList::factory()->for($this->owner)->create([
            'name' => 'owners-private-list',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $this->publicLink = Link::factory()->for($this->owner)->create([
            'url' => 'https://public-link.com',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);

        $this->publicLink->tags()->attach($this->privateTag->id);
        $this->publicLink->lists()->attach($this->privateList->id);

        $this->actingAs($this->viewer);
    }

    public function test_link_listing_does_not_leak_private_tags_of_other_users(): void
    {
        $response = $this->get('links');

        $response->assertOk()
            ->assertSee($this->publicLink->url)
            ->assertDontSee($this->privateTag->name);
    }

    public function test_link_listing_still_shows_own_private_tags(): void
    {
        $ownTag = Tag::factory()->for($this->viewer)->create([
            'name' => 'viewers-private-tag',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $ownLink = Link::factory()->for($this->viewer)->create([
            'url' => 'https://own-link.com',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $ownLink->tags()->attach($ownTag->id);

        $this->get('links')
            ->assertOk()
            ->assertSee($ownTag->name);
    }

    public function test_search_does_not_leak_private_tags_of_other_users(): void
    {
        config([
            'linkace.search.driver' => 'database',
            'scout.driver' => 'database',
        ]);

        $response = $this->get('search?' . http_build_query([
            'query' => 'public-link',
        ]));

        $response->assertOk()
            ->assertSee($this->publicLink->url)
            ->assertDontSee($this->privateTag->name);
    }

    public function test_link_form_does_not_resolve_private_taxonomy_of_other_users(): void
    {
        // The link form redisplays submitted tag/list IDs by name after a
        // validation error, which turns the form into an oracle for any ID.
        $this->submitInvalidLinkForm([$this->privateTag->id], [$this->privateList->id]);

        $this->get('links/create')
            ->assertOk()
            ->assertDontSee($this->privateTag->name)
            ->assertDontSee($this->privateList->name);
    }

    public function test_link_form_still_resolves_own_private_taxonomy(): void
    {
        $ownTag = Tag::factory()->for($this->viewer)->create([
            'name' => 'viewers-private-tag',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $ownList = LinkList::factory()->for($this->viewer)->create([
            'name' => 'viewers-private-list',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $this->submitInvalidLinkForm([$ownTag->id], [$ownList->id]);

        $this->get('links/create')
            ->assertOk()
            ->assertSee($ownTag->name)
            ->assertSee($ownList->name);
    }

    /**
     * Submitting the link form without a URL fails validation, which redirects
     * back to the form with the submitted tag/list IDs flashed as old input.
     */
    private function submitInvalidLinkForm(array $tagIds, array $listIds): void
    {
        $this->from('links/create')
            ->post('links', [
                'url' => '',
                'tags' => json_encode($tagIds),
                'lists' => json_encode($listIds),
            ])
            ->assertRedirect('links/create');
    }

    public function test_tag_relation_modifier_does_not_resolve_private_tags_of_other_users(): void
    {
        $modifier = new TagRelationModifier();

        $this->assertStringNotContainsString($this->privateTag->name, (string) $modifier->modify([
            $this->privateTag->id,
        ]));
    }

    public function test_tag_relation_modifier_still_resolves_own_tags(): void
    {
        $ownTag = Tag::factory()->for($this->viewer)->create([
            'name' => 'viewers-private-tag',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $modifier = new TagRelationModifier();

        $this->assertEquals($ownTag->name, $modifier->modify([$ownTag->id]));
        $this->assertNull($modifier->modify(null));
    }

    public function test_list_relation_modifier_does_not_resolve_private_lists_of_other_users(): void
    {
        $modifier = new ListRelationModifier();

        $this->assertStringNotContainsString($this->privateList->name, (string) $modifier->modify([
            $this->privateList->id,
        ]));
    }

    public function test_list_relation_modifier_still_resolves_own_lists(): void
    {
        $ownList = LinkList::factory()->for($this->viewer)->create([
            'name' => 'viewers-private-list',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $modifier = new ListRelationModifier();

        $this->assertEquals($ownList->name, $modifier->modify([$ownList->id]));
        $this->assertNull($modifier->modify(null));
    }
}
