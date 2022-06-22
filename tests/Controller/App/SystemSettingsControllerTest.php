<?php

namespace Tests\Controller\App;

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
    }

    public function testValidSettingsResponse(): void
    {
        $response = $this->get('settings/system');

        $response->assertOk()
            ->assertSee('Cron Token')
            ->assertSee('System Settings');
    }

    public function testValidSettingsUpdateResponse(): void
    {
        $response = $this->get('dashboard');
        $response->assertDontSee('Begin of custom header scripts');

        $response = $this->post('settings/system', [
            'page_title' => 'New Title',
            'guest_access' => '1',
            'custom_header_content' => '<script>console.log(\'scripts work\')</script>',
        ]);

        $response->assertRedirect('settings/system');

        $this->assertEquals('New Title', systemsettings('page_title'));
        $this->assertEquals(true, systemsettings('guest_access'));
        $this->assertEquals('<script>console.log(\'scripts work\')</script>', systemsettings('custom_header_content'));

        $response = $this->get('dashboard');
        $response->assertSee('<script>console.log(\'scripts work\')</script>', false);
    }

    public function testValidCronGeneratonResponse(): void
    {
        $response = $this->post('settings/generate-cron-token');

        $response->assertOk()
            ->assertJsonStructure([
                'new_token',
            ]);

        $this->assertNotNull(systemsettings('cron_token'));
    }
}
