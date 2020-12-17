<?php

namespace Tests\Controller\Guest;

use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::create([
            'key' => 'system_guest_access',
            'value' => '1',
        ]);
    }

    public function testValidTagOverviewResponse(): void
    {
        User::factory()->create();

        Tag::factory()->create([
            'name' => 'public tag',
            'is_private' => false
        ]);

        Tag::factory()->create([
            'name' => 'private tag',
            'is_private' => true
        ]);

        $response = $this->get('guest/tags');

        $response->assertOk()
            ->assertSee('public tag')
            ->assertDontSee('private tag');
    }

    public function testValidTagDetailResponse(): void
    {
        User::factory()->create();

        Tag::factory()->create([
            'name' => 'testTag',
            'is_private' => false,
        ]);

        $response = $this->get('guest/tags/1');

        $response->assertOk()
            ->assertSee('testTag');
    }

    public function testInvalidTagDetailResponse(): void
    {
        User::factory()->create();

        Tag::factory()->create(['is_private' => true]);

        $response = $this->get('guest/tags/1');

        $response->assertNotFound();
    }
}
