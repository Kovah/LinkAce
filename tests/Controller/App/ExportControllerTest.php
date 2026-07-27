<?php

namespace Tests\Controller\App;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('ExampleSeeder');

        $this->user = User::notSystem()->first();
        $this->actingAs($this->user);
    }

    public function test_valid_export_response(): void
    {
        $response = $this->get('export');

        $response->assertOk()
            ->assertSee('Export');
    }

    public function test_valid_html_export_generation(): void
    {
        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $response = $this->post('export/html');
        $response->assertOk();

        $content = $response->streamedContent();

        $this->assertStringContainsString(
            '<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">',
            $content
        );
        $this->assertStringNotContainsString($otherLink->url, $content);
    }

    public function test_html_export_does_not_leak_private_tags_of_other_users(): void
    {
        // Simulates a pre-existing cross-user link_tags pivot association, e.g.
        // created via import/sync, where the exporting user's link ends up
        // tagged with another user's private tag.
        $otherUser = User::factory()->create();
        $privateTag = Tag::factory()->for($otherUser)->create([
            'name' => 'other-users-private-tag',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $ownLink = Link::factory()->for($this->user)->create();
        $ownLink->tags()->attach($privateTag->id);

        $response = $this->post('export/html');
        $response->assertOk();

        $content = $response->streamedContent();

        $this->assertStringNotContainsString($privateTag->name, $content);
    }

    public function test_valid_csv_export_generation(): void
    {
        /** @var Link $link */
        $link = Link::inRandomOrder()->first();

        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $response = $this->post('export/csv');
        $response->assertOk();

        $content = $response->streamedContent();

        $this->assertStringContainsString(
            sprintf('%s,%s,%s', $link->id, $link->user_id, $link->url),
            $content
        );
        $this->assertStringNotContainsString($otherLink->url, $content);
    }
}
