<?php

namespace Tests\Controller\App;

use App\Enums\ModelAttribute;
use App\Models\User;
use App\Settings\UserSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ImportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function testValidImportResponse(): void
    {
        $response = $this->get('import');

        $response->assertOk()->assertSee('Import');
    }

    public function testValidImportActionResponse(): void
    {
        $response = $this->importBookmarks();

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseCount('links', 5);
        $this->assertDatabaseCount('tags', 18);
    }

    public function testDatelessImportActionResponse(): void
    {
        $this->travelTo(Carbon::create(2024, 2, 20));

        $response = $this->importBookmarks('/data/import_example_dateless.html');

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseCount('links', 5);
        $this->assertDatabaseCount('tags', 18);
        $this->assertDatabaseHas('links', [
            'url' => 'https://loader.io/',
            'created_at' => '2024-02-20 00:00:00'
        ]);
    }

    public function testImportWithPrivateDefaults(): void
    {
        $settings = app(UserSettings::class);
        $settings->links_default_visibility = ModelAttribute::VISIBILITY_PRIVATE;
        $settings->tags_default_visibility = ModelAttribute::VISIBILITY_PRIVATE;
        $settings->save();

        $response = $this->importBookmarks();

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseCount('links', 5);

        $this->assertDatabaseHas('links', [
            'url' => 'https://loader.io/',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $this->assertDatabaseHas('tags', [
            'name' => 'article',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);
    }

    protected function importBookmarks(string $importFile = '/data/import_example.html'): TestResponse
    {
        $testHtml = '<!DOCTYPE html><head><title>DuckDuckGo</title></head></html>';
        Http::fake(['*' => Http::response($testHtml)]);

        $exampleData = file_get_contents(__DIR__ . $importFile);
        $file = UploadedFile::fake()->createWithContent('import_example.html', $exampleData);

        return $this->post('import', [
            'import-file' => $file,
        ], [
            'Accept' => 'application/json',
        ]);
    }
}
