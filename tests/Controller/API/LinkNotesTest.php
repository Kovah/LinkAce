<?php

namespace Tests\Controller\API;

use App\Models\Link;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LinkNotesTest extends ApiTestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    public function testLinksRequest(): void
    {
        $link = factory(Link::class)->create();
        $note = factory(Note::class)->create([
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
        factory(Link::class)->create();

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
