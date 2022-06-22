<?php

namespace Tests\Controller\App;

use App\Settings\SettingsAudit;
use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class CronControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidCronTokenResponse(): void
    {
        Artisan::shouldReceive('call')->with('schedule:run');

        $cronToken = Str::random(32);

        SystemSettings::fake([
            'cron_token' => $cronToken,
            'setup_completed' => true,
        ]);

        $response = $this->get('cron/' . $cronToken);

        $response->assertOk()
            ->assertSee('Cron successfully executed');
    }

    public function testInvalidCronTokenResponse(): void
    {
        $cronToken = Str::random(32);
        $invalidCronToken = Str::random(32);

        SystemSettings::fake([
            'cron_token' => $cronToken,
            'setup_completed' => true,
        ]);

        $response = $this->get('cron/' . $invalidCronToken);

        $response->assertForbidden()
            ->assertSee('The provided cron token is invalid');
    }

    public function testMissingCronTokenResponse(): void
    {
        $response = $this->get('cron');

        $response->assertNotFound();
    }
}
