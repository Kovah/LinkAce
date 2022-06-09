<?php

namespace Tests\Components\History;

use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use App\View\Components\History\ListEntry;
use App\View\Components\History\TagEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagEntryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function testRegularChange(): void
    {
        $tag = Tag::factory()->create([
            'name' => 'Test Tag',
        ]);

        $tag->update(['name' => 'New Tag']);

        $historyEntry = $tag->audits()->first();

        $output = (new TagEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Tag Name from <code>Test Tag</code> to <code>New Tag</code>',
            $output
        );
    }

    public function testModelDeletion(): void
    {
        $tag = Tag::factory()->create();

        $tag->delete();
        $tag->restore();

        $historyEntries = $tag->audits()->get();

        $output = (new TagEntry($historyEntries[0]))->render();
        $this->assertStringContainsString('Tag was deleted', $output);

        $output = (new TagEntry($historyEntries[1]))->render();
        $this->assertStringContainsString('Tag was restored', $output);
    }
}
