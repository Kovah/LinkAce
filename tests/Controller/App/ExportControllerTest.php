<?php

namespace Tests\Controller\App;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed('ExampleSeeder');

        $this->user = User::first();
        $this->actingAs($this->user);
    }

    public function testValidExportResponse(): void
    {
        $response = $this->get('export');

        $response->assertOk()
            ->assertSee('Export');
    }

    public function testValidHtmlExportGeneration(): void
    {
        $response = $this->post('export/html');
        $response->assertOk();

        $content = $response->streamedContent();

        $this->assertStringContainsString(
            '<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">',
            $content
        );
    }

    public function testValidCsvExportGeneration(): void
    {
        /** @var Link $link */
        $link = Link::inRandomOrder()->first();

        $response = $this->post('export/csv');
        $response->assertOk();

        $content = $response->streamedContent();

        $this->assertStringContainsString(
            sprintf('%s,%s,%s', $link->id, $link->user_id, $link->url),
            $content
        );
    }
}
