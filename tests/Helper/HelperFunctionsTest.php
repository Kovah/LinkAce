<?php

namespace Tests\Helper;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HelperFunctionsTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    private $user;

    /** @var Link */
    private $link;

    public function setUp(): void
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

        $this->user->load('rawSettings'); // Reload cached settings from other tests

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
        $this->actingAs($this->user);

        $this->post('settings/system', [
            'system_page_title' => 'New Title',
            'system_guest_access' => '1',
        ]);

        $settings = systemsettings();

        $this->assertArrayHasKey('system_page_title', $settings);
        $this->assertEquals('New Title', $settings['system_page_title']);
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

        $this->user->load('rawSettings'); // Reload cached settings from other tests

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

        $this->user->load('rawSettings'); // Reload cached settings from other tests

        $limit = getPaginationLimit();

        $this->assertEquals('100', $limit);
    }

    /**
     * Test the saveToArchive() helper function with a valid URL.
     * Should return true.
     */
    public function testValidWaybackLink(): void
    {
        $expected = 'https://web.archive.org/web/*/' . $this->link->url;

        $link = waybackLink($this->link);

        $this->assertEquals($expected, $link);
    }

    /**
     * Test the saveToArchive() helper function with an invalid URL.
     * Will return false.
     */
    public function testInvalidWaybackLink(): void
    {
        $url = 'not an URL';

        $link = waybackLink($url);

        $this->assertNull($link);
    }
}
