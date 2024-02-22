<?php

namespace Tests\Controller\Models;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function testAuthorComponent(): void
    {
        $user = User::factory()->create(['name' => 'MrTestUser']);

        Link::factory()->create(['user_id' => $user->id, 'title' => 'MrTestUser Link']);

        $response = $this->get('links/1');
        $response->assertOk()->assertSee('MrTestUser')->assertSee('users/MrTestUser');

        $user->delete();

        $response = $this->get('links/1');
        $response->assertOk()->assertSee('MrTestUser')->assertSee('User deleted');
    }

    public function testUserProfile(): void
    {
        $user = User::factory()->create(['name' => 'MrTestUser']);

        Link::factory()->create(['user_id' => $user->id, 'title' => 'MrTestUser Link']);
        LinkList::factory()->create(['user_id' => $user->id, 'name' => 'MrTestUser List']);
        Tag::factory()->create(['user_id' => $user->id, 'name' => 'MrTestUser Tag']);

        $response = $this->get('users/MrTestUser');
        $response->assertOk()
            ->assertSee('MrTestUser')
            ->assertSee('MrTestUser Link')
            ->assertSee('MrTestUser List')
            ->assertSee('MrTestUser Tag');
    }
}
