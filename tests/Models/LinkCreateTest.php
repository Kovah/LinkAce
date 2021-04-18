<?php

namespace Tests\Models;

use App\Models\User;
use App\Repositories\LinkRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LinkCreateTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @var User */
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $testHtml = '<!DOCTYPE html><head>' .
            '<title>DuckDuckGo</title>' .
            '<meta name="test" content="Bla">' .
            '<meta name="description" content="This an example description">' .
            '<meta property="og:image" content="https://duckduckgo.com/assets/logo_social-media.png">' .
            '</head></html>';

        Http::fake(['*' => Http::response($testHtml)]);

        $this->user = User::factory()->create();
    }

    public function testValidLinkCreation(): void
    {
        $this->be($this->user);

        $url = 'https://duckduckgo.com/';

        $originalData = [
            'url' => $url,
            'title' => null,
            'description' => null,
            'is_private' => false,
        ];

        $link = LinkRepository::create($originalData);

        $assertedData = [
            'id' => $link->id,
            'url' => $url,
            'title' => 'DuckDuckGo',
            'description' => 'This an example description',
            'icon' => 'link',
            'thumbnail' => 'https://duckduckgo.com/assets/logo_social-media.png',
            'is_private' => 0,
            'user_id' => 1,
            'status' => 1,
            'created_at' => $link->created_at,
            'updated_at' => $link->updated_at,
            'deleted_at' => null,
        ];

        $this->assertDatabaseHas('links', $assertedData);
    }
}
