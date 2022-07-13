<?php

namespace Tests\Controller\Traits;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\User;

trait PreparesTestData
{
    public function createTestLinks(?User $otherUser = null): void
    {
        $otherUser ??= User::factory()->create();

        Link::factory()->create(['url' => 'https://public-link.com']);
        Link::factory()->create([
            'url' => 'https://internal-link.com',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);
        Link::factory()->create([
            'url' => 'https://private-link.com',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);
    }

    public function createTestLists(?User $otherUser = null): void
    {
        $otherUser ??= User::factory()->create();

        LinkList::factory()->create(['name' => 'Public List']);
        LinkList::factory()->create([
            'name' => 'Internal List',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);
        LinkList::factory()->create([
            'name' => 'Private List',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);
    }
}
