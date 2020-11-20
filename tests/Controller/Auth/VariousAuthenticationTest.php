<?php

namespace Tests\Controller\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class VariousAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testConfirmPasswordView(): void
    {
        $user = User::factory()->create();

        $confirmView = $this->actingAs($user)->get('user/confirm-password');
        $confirmView->assertSee('Confirmation required');
    }
}
