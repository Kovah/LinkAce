<?php

namespace Tests\Controller\Guest;

use App\Models\LinkList;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ListControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::create([
            'key' => 'system_guest_access',
            'value' => '1',
        ]);
    }

    public function testValidListOverviewResponse(): void
    {
        factory(User::class)->create();

        $listPublic = factory(LinkList::class)->create(['is_private' => false]);
        $listPrivate = factory(LinkList::class)->create(['is_private' => true]);

        $response = $this->get('guest/lists');

        $response->assertOk()
            ->assertSee($listPublic->name)
            ->assertDontSee($listPrivate->name);
    }

    public function testValidListDetailResponse(): void
    {
        factory(User::class)->create();

        $listPublic = factory(LinkList::class)->create(['is_private' => false]);

        $response = $this->get('guest/lists/1');

        $response->assertOk()
            ->assertSee($listPublic->name);
    }

    public function testInvalidListDetailResponse(): void
    {
        factory(User::class)->create();

        factory(LinkList::class)->create(['is_private' => true]);

        $response = $this->get('guest/lists/1');

        $response->assertNotFound();
    }
}
