<?php

namespace Tests\Controller\API;

use App\Enums\ApiToken;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\Traits\PreparesTestData;

class LinkNotesTest extends ApiTestCase
{
    use PreparesTestData;
    use RefreshDatabase;

    public function test_links_request(): void
    {
        $this->createTestLinks();
        $this->createTestNotes(Link::find(2));

        $this->getJsonAuthorized('api/v2/links/1/notes')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['note' => 'Public Note'],
                ],
            ]);

        $this->getJsonAuthorized('api/v2/links/2/notes')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['note' => 'Internal Note'],
                ],
            ])
            ->assertJsonMissing([
                'data' => [
                    ['note' => 'Private Note'],
                ],
            ]);

        $this->getJsonAuthorized('api/v2/links/3/notes')
            ->assertForbidden();
    }

    public function test_forbidden_notes_request_from_system_without_notes_read(): void
    {
        $this->createTestLinks();
        $this->createTestNotes(Link::find(2));
        $this->createSystemToken([ApiToken::ABILITY_LINKS_READ]);

        $this->getJsonAuthorized('api/v2/links/2/notes', useSystemToken: true)
            ->assertForbidden();
    }

    public function test_notes_request_from_system_with_notes_read(): void
    {
        $this->createTestLinks();
        $this->createTestNotes(Link::find(2));
        $this->createSystemToken([ApiToken::ABILITY_LINKS_READ, ApiToken::ABILITY_NOTES_READ]);

        $this->getJsonAuthorized('api/v2/links/2/notes', useSystemToken: true)
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['note' => 'Internal Note'],
                ],
            ])
            ->assertJsonMissing([
                'data' => [
                    ['note' => 'Private Note'],
                ],
            ]);
    }

    public function test_links_request_without_links(): void
    {
        Link::factory()->create();

        $this->getJsonAuthorized('api/v2/links/1/notes')
            ->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    public function test_show_request_not_found(): void
    {
        $this->getJsonAuthorized('api/v2/links/1/notes')->assertNotFound();
    }
}
