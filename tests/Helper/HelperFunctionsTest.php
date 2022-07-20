<?php

namespace Tests\Helper;

use App\Enums\Role;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HelperFunctionsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Link $link;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->link = Link::factory()->create();
    }

    /**
     * Test the usersettings() helper function and try to get all user settings
     * at once.
     */
    public function testGetAllUserSettings(): void
    {
        $this->actingAs($this->user);

        $this->post('settings/app', [
            'locale' => 'en_US',
            'timezone' => 'Europe/Berlin',
        ]);

        $settings = usersettings();

        $this->assertArrayHasKey('locale', $settings);
        $this->assertEquals('en_US', $settings['locale']);
        $this->assertArrayHasKey('timezone', $settings);
        $this->assertEquals('Europe/Berlin', $settings['timezone']);
    }

    /**
     * Test the systemsettings() helper function and try to get all system
     * settings at once.
     */
    public function testGetAllSystemSettings(): void
    {
        $this->user->assignRole(Role::ADMIN);
        $this->actingAs($this->user);

        $this->post('settings/system', [
            'page_title' => 'New Title',
            'guest_access' => '1',
        ]);

        $settings = systemsettings();

        $this->assertArrayHasKey('page_title', $settings);
        $this->assertEquals('New Title', $settings['page_title']);
    }

    /**
     * Test the formatDateTime() helper with a specific user format set first.
     */
    public function testDateTimeFormatterWithUserSettings(): void
    {
        $this->actingAs($this->user);

        $this->post('settings/app', [
            'locale' => 'en_US',
            'timezone' => 'Europe/Berlin',
            'date_format' => 'd.m.Y',
            'time_format' => 'H:i:s',
        ]);

        $dateTime = now();
        $appFormatted = formatDateTime($dateTime);
        $carbonFormatted = $dateTime->format('d.m.Y H:i:s');

        $this->assertEquals($carbonFormatted, $appFormatted);
    }

    /**
     * Test the formatDateTime() helper with a specific user format set first.
     */
    public function testPaginationLimitWithUserSettings(): void
    {
        $this->actingAs($this->user);

        $this->post('settings/app', [
            'locale' => 'en_US',
            'timezone' => 'Europe/Berlin',
            'listitem_count' => '100',
        ]);

        $limit = getPaginationLimit();

        $this->assertEquals('100', $limit);
    }
}
