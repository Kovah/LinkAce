<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SystemSettingsControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    public function testValidExportResponse(): void
    {
        $response = $this->get('settings/system');

        $response->assertStatus(200)
            ->assertSee('Cron Token')
            ->assertSee('System Settings');
    }

    public function testValidSettingsUpdateResponse(): void
    {
        $response = $this->post('settings/system', [
            'system_page_title' => 'New Title',
            'system_guest_access' => '1',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('settings', [
            'id' => 1,
            'user_id' => null,
            'key' => 'system_page_title',
            'value' => 'New Title',
        ]);

        $this->assertDatabaseHas('settings', [
            'id' => 2,
            'user_id' => null,
            'key' => 'system_guest_access',
            'value' => '1',
        ]);
    }

    public function testValidCronGeneratonResponse(): void
    {
        $response = $this->post('settings/generate-cron-token');

        $response->assertStatus(200)->assertJsonStructure([
            'new_token',
        ]);
    }
}
