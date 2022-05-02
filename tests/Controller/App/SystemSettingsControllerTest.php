<?php

namespace Tests\Controller\App;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemSettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    private $user;

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
            'system_page_title' => 'New Title',
            'system_guest_access' => '1',
            'system_custom_header_content' => '<script>console.log(\'scripts work\')</script>',
        ]);

        $response->assertRedirect('settings/system');

        $this->assertDatabaseHas('settings', [
            'user_id' => null,
            'key' => 'system_page_title',
            'value' => 'New Title',
        ]);

        $this->assertDatabaseHas('settings', [
            'user_id' => null,
            'key' => 'system_guest_access',
            'value' => '1',
        ]);

        $this->assertDatabaseHas('settings', [
            'user_id' => null,
            'key' => 'system_custom_header_content',
            'value' => '<script>console.log(\'scripts work\')</script>',
        ]);

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
    }
}
