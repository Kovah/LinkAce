<?php

namespace Tests\Controller\App;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemSettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->user->assignRole(Role::ADMIN);
    }

    public function test_settings_access_for_users(): void
    {
        // No access for regular users
        $this->user->syncRoles(Role::USER);

        $response = $this->get('settings/system');
        $response->assertForbidden();

        // Access granted for admins
        $this->user->syncRoles(Role::ADMIN);

        $response = $this->get('settings/system');
        $response->assertOk();
    }

    public function test_valid_settings_response(): void
    {
        $response = $this->get('settings/system');

        $response->assertOk()
            ->assertSee('Cron Token')
            ->assertSee('System Settings');
    }

    public function test_valid_settings_update_response(): void
    {
        $response = $this->get('dashboard');
        $response->assertDontSee('Begin of custom header scripts');

        $response = $this->post('settings/system', [
            'page_title' => 'New HTML Title',
            'logo_text' => 'Meine Bookmarks',
            'additional_footer_link_url' => 'https://woblick.dev',
            'additional_footer_link_text' => 'Portfolio',
            'contact_page_enabled' => '1',
            'contact_page_title' => 'ContactPage',
            'contact_page_content' => '**Example** with [link](https://woblick.dev)',
            'custom_header_content' => '<script>console.log(\'scripts work\')</script>',
        ]);

        $response->assertRedirect('settings/system');

        $this->assertEquals('New HTML Title', systemsettings('page_title'));
        $this->assertEquals('Meine Bookmarks', systemsettings('logo_text'));
        $this->assertEquals('https://woblick.dev', systemsettings('additional_footer_link_url'));
        $this->assertEquals('Portfolio', systemsettings('additional_footer_link_text'));
        $this->assertTrue(systemsettings('contact_page_enabled'));

        $this->get('contact')
            ->assertSee('<script>console.log(\'scripts work\')</script>', false)
            ->assertSee('New HTML Title')
            ->assertSee('Meine Bookmarks')
            ->assertSee('Portfolio')
            ->assertSee('ContactPage')
            ->assertSee('<strong>Example</strong> with <a href="https://woblick.dev">link</a>', false);
    }

    public function test_contact_page_does_not_render_unsafe_markdown_links(): void
    {
        // Regression test for GHSA-pm4x-ww2f-xwvp: contact_page_content is rendered
        // through Str::markdown() into raw Blade output on the public /contact page,
        // so a `javascript:` Markdown link must not be rendered with a live `href`.
        $this->post('settings/system', [
            'page_title' => 'New HTML Title',
            'logo_text' => 'Meine Bookmarks',
            'contact_page_enabled' => '1',
            'contact_page_title' => 'ContactPage',
            'contact_page_content' => '[Open link](javascript:alert(document.cookie)) and [safe link](https://woblick.dev)',
        ])->assertRedirect('settings/system');

        $response = $this->get('contact');

        $response->assertDontSee('href="javascript:', false);
        $response->assertSee('<a href="https://woblick.dev">safe link</a>', false);
    }

    public function test_valid_guest_settings_update_response(): void
    {
        $response = $this->get('dashboard');
        $response->assertDontSee('Begin of custom header scripts');

        $response = $this->post('settings/system/guest', [
            'guest_access_enabled' => '1',
            'locale' => 'de_DE',
        ]);

        $response->assertRedirect('settings/system');

        $this->assertTrue(systemsettings('guest_access_enabled'));
        $this->assertEquals('de_DE', guestsettings('locale'));

        auth()->logout();
        $response = $this->get('guest/links');
        $response->assertSee('Listen');
    }

    public function test_oversized_system_settings_are_rejected(): void
    {
        $response = $this->post('settings/system', [
            'page_title' => str_repeat('a', 300),
            'logo_text' => str_repeat('b', 50),
        ]);

        $response->assertSessionHasErrors(['page_title', 'logo_text']);

        $this->assertNull(systemsettings('page_title'));
        $this->assertNull(systemsettings('logo_text'));
    }

    public function test_unknown_system_settings_keys_are_not_saved(): void
    {
        $token = systemsettings('cron_token');

        $this->post('settings/system', [
            'page_title' => 'New HTML Title',
            'cron_token' => 'attacker-controlled-token',
            'setup_completed' => '0',
        ])->assertSessionHasNoErrors();

        $this->assertEquals('New HTML Title', systemsettings('page_title'));
        $this->assertEquals($token, systemsettings('cron_token'));
        $this->assertTrue(systemsettings('setup_completed'));
    }

    public function test_invalid_guest_locale_is_rejected(): void
    {
        $response = $this->post('settings/system/guest', [
            'guest_access_enabled' => '1',
            'locale' => '../../../../tmp/x',
        ]);

        $response->assertSessionHasErrors('locale');

        $this->assertEquals('en_US', guestsettings('locale'));
    }

    public function test_unknown_guest_locale_is_rejected(): void
    {
        $response = $this->post('settings/system/guest', [
            'guest_access_enabled' => '1',
            'locale' => 'zz_ZZ',
        ]);

        $response->assertSessionHasErrors('locale');

        $this->assertEquals('en_US', guestsettings('locale'));
    }

    public function test_invalid_guest_locale_does_not_break_guest_pages(): void
    {
        $this->post('settings/system/guest', [
            'guest_access_enabled' => '1',
            'locale' => '../../../../tmp/x',
        ]);

        auth()->logout();

        $this->get('login')->assertOk();
    }

    public function test_invalid_guest_listitem_count_is_rejected(): void
    {
        $response = $this->post('settings/system/guest', [
            'guest_access_enabled' => '1',
            'locale' => 'en_US',
            'listitem_count' => '999999',
        ]);

        $response->assertSessionHasErrors('listitem_count');

        $this->assertEquals(24, guestsettings('listitem_count'));
    }

    public function test_invalid_guest_darkmode_setting_is_rejected(): void
    {
        $response = $this->post('settings/system/guest', [
            'guest_access_enabled' => '1',
            'locale' => 'en_US',
            'darkmode_setting' => '99',
        ]);

        $response->assertSessionHasErrors('darkmode_setting');

        $this->assertEquals(1, guestsettings('darkmode_setting'));
    }

    public function test_unknown_guest_settings_keys_are_not_saved(): void
    {
        $this->post('settings/system/guest', [
            'guest_access_enabled' => '1',
            'locale' => 'en_US',
            'profile_is_public' => '1',
        ])->assertSessionHasNoErrors();

        $this->assertNull(guestsettings('profile_is_public'));
    }

    public function test_valid_cron_generaton_response(): void
    {
        $response = $this->post('settings/generate-cron-token');

        $response->assertOk()
            ->assertJsonStructure([
                'new_token',
            ]);

        $this->assertNotNull(systemsettings('cron_token'));
    }
}
