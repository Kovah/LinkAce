<?php

namespace Tests\Controller\Traits;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\User;

trait PreparesTestLinks
{
    public function createTestLinks(?User $otherUser = null): void
    {
        $otherUser ??= User::factory()->create();

        Link::factory()->create(['url' => 'https://public-link.com']);
        Link::factory()->create(['url' => 'https://internal-link.com', 'user_id' => $otherUser->id]);
        Link::factory()->create([
            'url' => 'https://private-link.com',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);
    }
}
