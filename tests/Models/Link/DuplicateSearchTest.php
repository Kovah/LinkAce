<?php

namespace Tests\Models\Link;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DuplicateSearchTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_without_duplicates(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isEmpty());
    }

    public function test_scheme(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'http://example.com',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function test_trailing_slashes(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://example.com/',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function test_url_fragments(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://example.com#anchor',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function test_query_parameters(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://example.com?s=testing',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function test_url_with_port(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://localhost:8080',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://localhost:8080/',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function test_url_with_simple_auth(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://:password@ftp.cia.gov',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://:password@ftp.cia.gov/',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function test_url_with_full_auth(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://:snowden@files.nsa.gov',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://admin:snowden@files.nsa.gov/',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function test_broken_url(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'example.com',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isEmpty());
    }

    public function test_other_users_private_link_not_detected_as_duplicate(): void
    {
        $otherUser = User::factory()->create();

        $link = Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        Link::factory()->create([
            'url' => 'http://example.com',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isEmpty());
    }

    public function test_private_duplicate_owned_by_link_owner_is_detected_for_different_current_user(): void
    {
        $owner = User::factory()->create();
        $currentUser = User::factory()->create();
        $this->actingAs($currentUser);

        $link = Link::factory()->create([
            'url' => 'https://example.com',
            'user_id' => $owner->id,
        ]);

        $duplicateLink = Link::factory()->create([
            'url' => 'http://example.com',
            'user_id' => $owner->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }
}
