<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExportControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

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

        $response->assertStatus(200)
            ->assertSee('Export');
    }

    public function testValidExportGenerationResponse(): void
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->post('export');
        $response->assertStatus(200);

        $content = $response->streamedContent();

        $this->assertStringContainsString(
            '<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">',
            $content
        );
    }
}
