<?php

namespace Tests\Controller\App;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CronControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidCronTokenResponse(): void
    {
        $cronToken = Str::random(32);

        Setting::create([
            'key' => 'cron_token',
            'value' => $cronToken,
        ]);

        $response = $this->get('cron/' . $cronToken);

        $response->assertOk()
            ->assertSee('Cron successfully executed');
    }

    public function testInvalidCronTokenResponse(): void
    {
        $cronToken = Str::random(32);
        $invalidCronToken = Str::random(32);

        Setting::create([
            'key' => 'cron_token',
            'value' => $cronToken,
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
