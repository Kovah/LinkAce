<?php

namespace Tests\Controller\Models;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
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

    public function test_author_component(): void
    {
        $user = User::factory()->create(['name' => 'MrTestUser']);

        Link::factory()->create(['user_id' => $user->id, 'title' => 'MrTestUser Link']);

        $response = $this->get('links/1');
        $response->assertOk()->assertSee('MrTestUser')->assertSee('users/MrTestUser');

        $user->delete();

        $response = $this->get('links/1');
        $response->assertOk()->assertSee('MrTestUser')->assertSee('User deleted');
    }

    public function test_user_profile(): void
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

    public function test_user_profile_excludes_private_items(): void
    {
        $profileUser = User::factory()->create(['name' => 'VictimUser']);

        Link::factory()->create([
            'user_id' => $profileUser->id,
            'title' => 'Public Link',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        Link::factory()->create([
            'user_id' => $profileUser->id,
            'title' => 'Private Link',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        LinkList::factory()->create([
            'user_id' => $profileUser->id,
            'name' => 'Public List',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        LinkList::factory()->create([
            'user_id' => $profileUser->id,
            'name' => 'Private List',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        Tag::factory()->create([
            'user_id' => $profileUser->id,
            'name' => 'Public Tag',
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ]);
        Tag::factory()->create([
            'user_id' => $profileUser->id,
            'name' => 'Private Tag',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $response = $this->get('users/VictimUser');
        $response->assertOk()
            ->assertSee('VictimUser')
            ->assertSee('Public Link')
            ->assertSee('Public List')
            ->assertSee('Public Tag')
            ->assertDontSee('Private Link')
            ->assertDontSee('Private List')
            ->assertDontSee('Private Tag');
    }
}
