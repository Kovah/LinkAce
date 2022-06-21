<?php

namespace Tests\Controller\App;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditControllerTest extends TestCase
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

    public function testAuditPage(): void
    {
        $response = $this->get('system/audit');

        $response->assertOk()->assertSee('System Events');
    }

    public function testAuditPageWithEntries(): void
    {
        $this->post('settings/generate-cron-token');

        $response = $this->get('system/audit');
        $response->assertSee('System: Cron Token was re-generated');
    }
}
