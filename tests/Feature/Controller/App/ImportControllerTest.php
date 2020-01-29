<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImportControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    public function testValidImportResponse(): void
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->get('import');

        $response->assertStatus(200)
            ->assertSee('Import');
    }

    public function testValidImportActionResponse(): void
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $exampleData = file_get_contents(__DIR__ . '/data/import_example.html');
        $file = UploadedFile::fake()->createWithContent('import_example.html', $exampleData);

        $response = $this->post('import', [
            'import-file' => $file,
        ]);

        $response->assertStatus(302);

        $linkCount = Link::count();
        $this->assertEquals(5, $linkCount);
    }

    public function testLoginRedirectForImport(): void
    {
        $response = $this->get('import');

        $response->assertStatus(302)
            ->assertRedirect('login');
    }
}
