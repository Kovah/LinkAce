<?php

namespace Tests\Controller\Guest;

use App\Models\LinkList;
use App\Models\User;
use App\Settings\SettingsAudit;
use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SystemSettings::fake([
            'guest_access_enabled' => true,
            'setup_completed' => true,
        ]);
    }

    public function testValidListOverviewResponse(): void
    {
        User::factory()->create();

        LinkList::factory()->create([
            'name' => 'public list',
            'is_private' => false,
        ]);
        LinkList::factory()->create([
            'name' => 'private list',
            'is_private' => true,
        ]);

        $response = $this->get('guest/lists');

        $response->assertOk()
            ->assertSee('public list')
            ->assertDontSee('private list');
    }

    public function testValidListDetailResponse(): void
    {
        User::factory()->create();

        LinkList::factory()->create([
            'name' => 'test list name',
            'is_private' => false,
        ]);

        $response = $this->get('guest/lists/1');

        $response->assertOk()->assertSee('test list name');
    }

    public function testInvalidListDetailResponse(): void
    {
        User::factory()->create();

        LinkList::factory()->create([
            'name' => 'test list name',
            'is_private' => true,
        ]);

        $response = $this->get('guest/lists/1');

        $response->assertNotFound();
    }
}
