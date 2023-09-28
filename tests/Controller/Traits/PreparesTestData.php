<?php

namespace Tests\Controller\Traits;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;

trait PreparesTestData
{
    public function createTestLinks(?User $otherUser = null): array
    {
        $otherUser ??= User::factory()->create();

        $link = Link::factory()->create([
            'url' => 'https://public-link.com',
            'created_at' => now()->subDay(),
        ]);
        $link2 = Link::factory()->create([
            'url' => 'https://internal-link.com',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
            'created_at' => now()->subHour(),
        ]);
        $link3 = Link::factory()->create([
            'url' => 'https://private-link.com',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'created_at' => now()->subMinute(),
        ]);

        return [$link, $link2, $link3];
    }

    public function createTestLists(?User $otherUser = null): array
    {
        $otherUser ??= User::factory()->create();

        $list = LinkList::factory()->create([
            'name' => 'Public List',
            'created_at' => now()->subDay(),
        ]);
        $list2 = LinkList::factory()->create([
            'name' => 'Internal List',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
            'created_at' => now()->subHour(),
        ]);
        $list3 = LinkList::factory()->create([
            'name' => 'Private List',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'created_at' => now()->subMinute(),
        ]);

        return [$list, $list2, $list3];
    }

    public function createTestTags(?User $otherUser = null): array
    {
        $otherUser ??= User::factory()->create();

        $tag1 = Tag::factory()->create([
            'name' => 'Public Tag',
            'created_at' => now()->subDay(),
        ]);
        $tag2 = Tag::factory()->create([
            'name' => 'Internal Tag',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
            'created_at' => now()->subHour(),
        ]);
        $tag3 = Tag::factory()->create([
            'name' => 'Private Tag',
            'user_id' => $otherUser->id,
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
            'created_at' => now()->subMinute(),
        ]);

        return [$tag1, $tag2, $tag3];
    }

    public function createTestNotes(?Link $linkForNotes = null, ?User $otherUser = null): array
    {
        $linkForNotes ??= Link::factory()->create();
        $otherUser ??= User::factory()->create();

        $note = Note::factory()->create(['note' => 'Public Note']);
        $note2 = Note::factory()->create([
            'link_id' => $linkForNotes->id,
            'user_id' => $otherUser->id,
            'note' => 'Internal Note',
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ]);
        $note3 = Note::factory()->create([
            'link_id' => $linkForNotes->id,
            'user_id' => $otherUser->id,
            'note' => 'Private Note',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        return [$note, $note2, $note3];
    }
}
