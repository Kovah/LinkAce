<?php

namespace Tests\Controller\App;

use App\Helper\HtmlMeta;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImportControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testValidImportResponse(): void
    {
        $response = $this->get('import');

        $response->assertOk()
            ->assertSee('Import');
    }

    public function testValidImportActionResponse(): void
    {
        $testHtml = '<!DOCTYPE html><head><title>DuckDuckGo</title></head></html>';
        Http::fake(['*' => Http::response($testHtml)]);

        $exampleData = file_get_contents(__DIR__ . '/data/import_example.html');
        $file = UploadedFile::fake()->createWithContent('import_example.html', $exampleData);

        $response = $this->post('import', [
            'import-file' => $file,
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $linkCount = Link::count();
        $this->assertEquals(5, $linkCount);
    }
}
