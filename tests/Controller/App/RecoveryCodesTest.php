<?php

namespace Tests\Controller\App;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RecoveryCodesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private array $codes = ['aaaa1-bbbb1', 'cccc2-dddd2'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'two_factor_secret' => encrypt('secret-key'),
            'two_factor_recovery_codes' => encrypt(json_encode($this->codes)),
        ]);
        $this->actingAs($this->user);
    }

    public function test_settings_page_does_not_contain_recovery_codes(): void
    {
        $response = $this->get('settings');

        $response->assertOk();

        foreach ($this->codes as $code) {
            $response->assertDontSee($code);
        }
    }

    public function test_recovery_codes_require_the_current_password(): void
    {
        $this->postJson('settings/recovery-codes', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors('current_password');

        $this->postJson('settings/recovery-codes', ['current_password' => 'wrongpassword'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('current_password');
    }

    public function test_recovery_codes_are_returned_for_the_correct_password(): void
    {
        $this->postJson('settings/recovery-codes', ['current_password' => 'secretpassword'])
            ->assertOk()
            ->assertJson(['codes' => $this->codes]);
    }

    public function test_recovery_codes_are_not_returned_without_two_factor_auth(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->postJson('settings/recovery-codes', ['current_password' => 'secretpassword'])
            ->assertStatus(422);
    }

    public function test_recovery_codes_require_authentication(): void
    {
        auth()->logout();

        $this->postJson('settings/recovery-codes', ['current_password' => 'secretpassword'])
            ->assertUnauthorized();
    }

    public function test_recovery_code_requests_are_rate_limited(): void
    {
        $responses = Collection::times(7, fn() => $this->postJson('settings/recovery-codes', [
            'current_password' => 'wrongpassword',
        ])->status());

        $this->assertEquals(429, $responses->last());
    }

    public function test_two_factor_fields_are_hidden_when_serialized(): void
    {
        $serialized = $this->user->toArray();

        $this->assertArrayNotHasKey('two_factor_secret', $serialized);
        $this->assertArrayNotHasKey('two_factor_recovery_codes', $serialized);
    }
}
