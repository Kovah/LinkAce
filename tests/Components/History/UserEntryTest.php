<?php

namespace Tests\Components\History;

use App\Models\User;
use App\View\Components\History\UserEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserEntryTest extends TestCase
{
    use RefreshDatabase;

    public function testRegularChange(): void
    {
        $user = User::factory()->create(['name' => 'TestUser']);

        $user->update(['name' => 'UserTest']);

        $historyEntries = $user->audits()->latest()->get();

        $output = (new UserEntry($historyEntries[0]))->render();
        $this->assertStringContainsString('User <code>TestUser</code> was created', $output);

        $output = (new UserEntry($historyEntries[1]))->render();
        $this->assertStringContainsString(
            'User 1: Changed Username from <code>TestUser</code> to <code>UserTest</code>',
            $output
        );
    }

    public function testModelDeletion(): void
    {
        $user = User::factory()->create(['name' => 'TestUser']);

        $user->delete();

        $historyEntries = $user->audits()->latest()->get();

        $output = (new UserEntry($historyEntries[1]))->render();
        $this->assertStringContainsString('User <code>TestUser</code> was deleted', $output);
    }
}
