<?php

namespace Tests\Controller\Guest;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use App\Settings\SystemSettings;
use App\Settings\UserSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testPublicUserProfile(): void
    {
        SystemSettings::fake(['setup_completed' => true, 'guest_access_enabled' => true]);

        User::factory()->create(['name' => 'MrTestUser']);

        Link::factory()->create(['url' => 'https://public.com', 'visibility' => ModelAttribute::VISIBILITY_PUBLIC]);
        Link::factory()->create(['url' => 'https://internal.com', 'visibility' => ModelAttribute::VISIBILITY_INTERNAL]);
        Link::factory()->create(['url' => 'https://private.com', 'visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        LinkList::factory()->create(['name' => 'Public List', 'visibility' => ModelAttribute::VISIBILITY_PUBLIC]);
        LinkList::factory()->create(['name' => 'Internal List', 'visibility' => ModelAttribute::VISIBILITY_INTERNAL]);
        LinkList::factory()->create(['name' => 'Private List', 'visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        Tag::factory()->create(['name' => 'Public Tag', 'visibility' => ModelAttribute::VISIBILITY_PUBLIC]);
        Tag::factory()->create(['name' => 'Internal Tag', 'visibility' => ModelAttribute::VISIBILITY_INTERNAL]);
        Tag::factory()->create(['name' => 'Private Tag', 'visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        UserSettings::setUserId(1);
        UserSettings::fake([
            'profile_is_public' => true,
        ]);

        $this->get('guest/users/MrTestUser')
            ->assertOk()
            ->assertSee('MrTestUser')
            ->assertSee('https://public.com')
            ->assertDontSee('https://internal.com')
            ->assertDontSee('https://private.com')
            ->assertSee('Public List')
            ->assertDontSee('Internal List')
            ->assertDontSee('Private List')
            ->assertSee('Public Tag')
            ->assertDontSee('Internal Tag')
            ->assertDontSee('Private Tag');
    }

    public function testPrivateUserProfile(): void
    {
        SystemSettings::fake(['setup_completed' => true, 'guest_access_enabled' => true]);

        User::factory()->create(['name' => 'MrPrivateUser']);

        UserSettings::fake([
            'profile_is_public' => false,
        ]);

        $this->get('guest/user/MrPrivateUser')->assertNotFound();
    }
}
