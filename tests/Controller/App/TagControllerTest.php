<?php

namespace Tests\Controller\App;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->setupTestData();
    }

    public function testValidTagResponse(): void
    {
        $response = $this->get('tags');

        $response->assertOk()
            ->assertSee('Tags');
    }

    public function testValidTagFilterResult(): void
    {
        $response = $this->get('tags?filter=Examples');

        $response->assertOk()
            ->assertSee('Examples')
            ->assertDontSee('No Tags found');
    }

    public function testTagFilterNoResult(): void
    {
        $response = $this->get('tags?filter=asdfasdfasdf');

        $response->assertOk()
            ->assertSee('No Tags found');
    }

    protected function setupTestData(): void
    {
        $tagExample = Tag::create([
            'name' => 'Examples',
            'user_id' => $this->user->id,
        ]);
    }
}
