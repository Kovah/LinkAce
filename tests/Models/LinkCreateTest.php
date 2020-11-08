<?php

namespace Tests\Models;

use App\Helper\HtmlMeta;
use App\Helper\LinkIconMapper;
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

        $testHtml = '<!DOCTYPE html><head><title>Google</title></head></html>';

        Http::fake([
            '*' => Http::response($testHtml, 200),
        ]);

        $this->user = User::factory()->create();
    }

    public function testValidLinkCreation(): void
    {
        $this->be($this->user);

        $url = 'https://google.com/';

        $meta = HtmlMeta::getFromUrl($url);

        $originalData = [
            'url' => $url,
            'title' => $meta['title'],
            'description' => $meta['description'],
            'is_private' => false,
        ];

        $link = LinkRepository::create($originalData);

        $automatedData = [
            'id' => $link->id,
            'icon' => LinkIconMapper::mapLink($url),
            'user_id' => auth()->user()->id,
            'status' => 1,
            'created_at' => $link->created_at,
            'updated_at' => $link->updated_at,
            'deleted_at' => null,
        ];

        $assertedData = array_merge($automatedData, $originalData);

        $this->assertDatabaseHas('links', $assertedData);
    }
}
