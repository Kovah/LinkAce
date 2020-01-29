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

    public function setUp(): void
    {
        parent::setUp();

        $this->seed('ExampleSeeder');
    }

    public function testValidExportResponse(): void
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

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

    public function testLoginRedirectForExport(): void
    {
        $response = $this->get('export');

        $response->assertStatus(302)
            ->assertRedirect('login');
    }
}
