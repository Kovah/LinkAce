<?php

namespace Tests\Controller\App;

use App\Enums\ModelAttribute;
use App\Jobs\ImportLinkJob;
use App\Models\Tag;
use App\Models\User;
use App\Settings\UserSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ImportControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testValidImportResponse(): void
    {
        $response = $this->get('import');

        $response->assertOk()->assertSee('Import');
    }

    public function testValidImportActionResponse(): void
    {
        Queue::fake();

        $exampleData = file_get_contents(__DIR__ . '/data/import_example.html');
        $file = UploadedFile::fake()->createWithContent('import_example.html', $exampleData);

        $response = $this->post('import', ['import-file' => $file], ['Accept' => 'application/json']);

        $response->assertOk()->assertJson(['success' => true]);

        Queue::assertPushed(ImportLinkJob::class, 5);
    }

    public function testQueuePage(): void
    {
        $exampleData = file_get_contents(__DIR__ . '/data/import_example.html');
        $file = UploadedFile::fake()->createWithContent('import_example.html', $exampleData);

        $response = $this->post('import', ['import-file' => $file], ['Accept' => 'application/json']);
        $response->assertOk()->assertJson(['success' => true]);

        $this->get('import/queue')->assertSeeInOrder([
            'https://medium.com/accelerated-intelligence',
            'https://adele.uxpin.com',
            'https://color.adobe.com/create/color-wheel',
            'https://loader.io',
            'https://astralapp.com',
        ]);
    }

    public function testLinkImportJob(): void
    {
        UserSettings::fake([
            'links_default_visibility' => ModelAttribute::VISIBILITY_INTERNAL,
            'tags_default_visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);

        $testHtml = '<!DOCTYPE html><head><title>DuckDuckGo</title></head></html>';
        Http::fake([
            'https://example.com/linkace-import.html' => Http::response($testHtml),
            '*' => Http::response(status: 404),
        ]);

        $linkData = [
            'name' => 'This is just an example',
            'image' => null,
            'url' => 'https://example.com/linkace-import.html',
            'tags' => [
                'article',
                'intelligence',
            ],
            'description' => 'Etiam habebis sem dicantur magna mollis euismod.',
            'dateCreated' => 1556456091,
            'public' => false,
        ];

        $importTag = Tag::create([
            'user_id' => $this->user->id,
            'name' => 'import-' . now()->format('YmdHis'),
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'created_at' => '2019-04-28 12:54:51',
        ]);

        (new ImportLinkJob($this->user->id, $linkData, $importTag, false))->handle();

        $this->assertDatabaseHas('links', [
            'user_id' => $this->user->id,
            'url' => 'https://example.com/linkace-import.html',
            'title' => 'This is just an example',
            'description' => 'Etiam habebis sem dicantur magna mollis euismod.',
            'visibility' => 3,
        ]);

        $this->assertDatabaseHas('tags', [
            'id' => 2,
            'name' => 'article',
        ]);

        $this->assertDatabaseHas('tags', [
            'id' => 3,
            'name' => 'intelligence',
        ]);

        $this->assertDatabaseHas('link_tags', [
            'link_id' => 1,
            'tag_id' => 2,
        ]);
        $this->assertDatabaseHas('link_tags', [
            'link_id' => 1,
            'tag_id' => 3,
        ]);
    }

    public function testLinkImportWithMeta(): void
    {
        UserSettings::fake([
            'links_default_visibility' => ModelAttribute::VISIBILITY_INTERNAL,
            'tags_default_visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);

        $testHtml = '<!DOCTYPE html><head><title>DuckDuckGo</title></head></html>';
        Http::fake([
            'https://example.com/linkace-import.html' => Http::response($testHtml),
            '*' => Http::response(status: 404),
        ]);

        $linkData = [
            'name' => '',
            'image' => null,
            'url' => 'https://example.com/linkace-import.html',
            'tags' => [
                'article',
                'intelligence',
            ],
            'description' => '',
            'dateCreated' => 1556456091,
            'public' => false,
        ];

        $importTag = Tag::create([
            'user_id' => $this->user->id,
            'name' => 'import-' . now()->format('YmdHis'),
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        (new ImportLinkJob($this->user->id, $linkData, $importTag, true))->handle();

        $this->assertDatabaseHas('links', [
            'user_id' => $this->user->id,
            'url' => 'https://example.com/linkace-import.html',
            'title' => 'DuckDuckGo',
            'description' => null,
            'visibility' => 3,
        ]);
    }

    public function testPublicLinkImport(): void
    {
        UserSettings::fake([
            'links_default_visibility' => ModelAttribute::VISIBILITY_INTERNAL,
            'tags_default_visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);

        $testHtml = '<!DOCTYPE html><head><title>DuckDuckGo</title></head></html>';
        Http::fake([
            'https://example.com/linkace-import.html' => Http::response($testHtml),
            '*' => Http::response(status: 404),
        ]);

        $linkData = [
            'name' => 'This is just an example',
            'image' => null,
            'url' => 'https://example.com/linkace-import.html',
            'tags' => [],
            'description' => 'Etiam habebis sem dicantur magna mollis euismod.',
            'dateCreated' => 1556456091,
            'public' => true,
        ];

        $importTag = Tag::create([
            'user_id' => $this->user->id,
            'name' => 'import-' . now()->format('YmdHis'),
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        (new ImportLinkJob($this->user->id, $linkData, $importTag, true))->handle();

        $this->assertDatabaseHas('links', [
            'user_id' => $this->user->id,
            'url' => 'https://example.com/linkace-import.html',
            'visibility' => 1,
        ]);
    }

    public function testLinkImportWithoutVisibility(): void
    {
        UserSettings::fake([
            'links_default_visibility' => ModelAttribute::VISIBILITY_INTERNAL,
            'tags_default_visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);

        $testHtml = '<!DOCTYPE html><head><title>DuckDuckGo</title></head></html>';
        Http::fake([
            'https://example.com/linkace-import.html' => Http::response($testHtml),
            '*' => Http::response(status: 404),
        ]);

        $linkData = [
            'name' => 'This is just an example',
            'image' => null,
            'url' => 'https://example.com/linkace-import.html',
            'tags' => [],
            'description' => 'Etiam habebis sem dicantur magna mollis euismod.',
            'dateCreated' => 1556456091,
            'public' => null,
        ];

        $importTag = Tag::create([
            'user_id' => $this->user->id,
            'name' => 'import-' . now()->format('YmdHis'),
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        (new ImportLinkJob($this->user->id, $linkData, $importTag, true))->handle();

        $this->assertDatabaseHas('links', [
            'user_id' => $this->user->id,
            'url' => 'https://example.com/linkace-import.html',
            'visibility' => 2,
        ]);
    }

    public function testLinkImportWithoutDate(): void
    {
        $this->travelTo(Carbon::create(2024, 6, 24, 12, 30));

        UserSettings::fake([
            'links_default_visibility' => ModelAttribute::VISIBILITY_INTERNAL,
            'tags_default_visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);

        $testHtml = '<!DOCTYPE html><head><title>DuckDuckGo</title></head></html>';
        Http::fake([
            'https://example.com/linkace-import.html' => Http::response($testHtml),
            '*' => Http::response(status: 404),
        ]);

        $linkData = [
            'name' => 'This is just an example',
            'image' => null,
            'url' => 'https://example.com/linkace-import.html',
            'tags' => [],
            'description' => 'Etiam habebis sem dicantur magna mollis euismod.',
            'dateCreated' => null,
            'public' => null,
        ];

        $importTag = Tag::create([
            'user_id' => $this->user->id,
            'name' => 'import-' . now()->format('YmdHis'),
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        (new ImportLinkJob($this->user->id, $linkData, $importTag, true))->handle();

        $this->assertDatabaseHas('links', [
            'user_id' => $this->user->id,
            'url' => 'https://example.com/linkace-import.html',
            'created_at' => '2024-06-24 12:30:00',
        ]);
    }
}
