<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinkNotesTest extends ApiTestCase
{
    use RefreshDatabase;

    public function testLinksRequest(): void
    {
        $link = Link::factory()->create();
        $note = Note::factory()->create([
            'link_id' => $link->id,
        ]);

        $response = $this->getJsonAuthorized('api/v1/links/1/notes');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    ['note' => $note->note],
                ],
            ]);
    }

    public function testLinksRequestWithoutLinks(): void
    {
        Link::factory()->create();

        $response = $this->getJsonAuthorized('api/v1/links/1/notes');

        $response->assertOk()
            ->assertJson([
                'data' => [],
            ]);

        $responseBody = json_decode($response->content());

        $this->assertEmpty($responseBody->data);
    }

    public function testShowRequestNotFound(): void
    {
        $response = $this->getJsonAuthorized('api/v1/links/1/notes');

        $response->assertNotFound();
    }
}
