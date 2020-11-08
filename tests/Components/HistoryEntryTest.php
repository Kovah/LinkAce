<?php

namespace Tests\Components;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use App\View\Components\Links\HistoryEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoryEntryTest extends TestCase
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

    public function testAddedChange(): void
    {
        $link = Link::factory()->create([
            'description' => null,
        ]);

        $link->description = 'Test Description';
        $link->save();

        $historyEntry = $link->revisionHistory()->first();

        $output = (new HistoryEntry($historyEntry))->render();

        $this->assertStringContainsString('Added <code>Test Description</code> to Description', $output);
    }

    public function testRegularChange(): void
    {
        $link = Link::factory()->create([
            'description' => 'Test Description',
        ]);

        $link->description = 'New Description';
        $link->save();

        $historyEntry = $link->revisionHistory()->first();

        $output = (new HistoryEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Description from <code>Test Description</code> to <code>New Description</code>',
            $output
        );
    }

    public function testRemoveChange(): void
    {
        $link = Link::factory()->create([
            'description' => 'Test Description',
        ]);

        $link->description = null;
        $link->save();

        $historyEntry = $link->revisionHistory()->first();

        $output = (new HistoryEntry($historyEntry))->render();

        $this->assertStringContainsString('Removed <code>Test Description</code> from Description', $output);
    }

    public function testModelDeletion(): void
    {
        $link = Link::factory()->create();

        $link->delete();
        $link->restore();

        $historyEntries = $link->revisionHistory;

        $output = (new HistoryEntry($historyEntries[0]))->render();
        $this->assertStringContainsString('Link was deleted', $output);

        $output = (new HistoryEntry($historyEntries[1]))->render();
        $this->assertStringContainsString('Link was restored', $output);
    }

    public function testTagsAddedChange(): void
    {
        $link = Link::factory()->create();

        $this->post('links/1', [
            '_method' => 'patch',
            'link_id' => $link->id,
            'url' => $link->url,
            'title' => $link->title,
            'description' => $link->description,
            'lists' => null,
            'tags' => 'newtag',
            'is_private' => $link->is_private,
        ]);

        $historyEntry = $link->revisionHistory()->latest()->first();

        $output = (new HistoryEntry($historyEntry))->render();

        $this->assertStringContainsString('Added <code>newtag</code> to Tags', $output);
    }

    public function testTagsChange(): void
    {
        $startTag = Tag::factory()->create();
        $link = Link::factory()->create();

        $link->tags()->sync($startTag->id);

        $this->post('links/1', [
            '_method' => 'patch',
            'link_id' => $link->id,
            'url' => $link->url,
            'title' => $link->title,
            'description' => $link->description,
            'lists' => null,
            'tags' => 'newtag',
            'is_private' => $link->is_private,
        ]);

        $historyEntry = $link->revisionHistory()->latest()->first();

        $output = (new HistoryEntry($historyEntry))->render();

        $this->assertStringContainsString(
            sprintf('Changed Tags from <code>%s</code> to <code>newtag</code>', $startTag->name),
            $output
        );
    }

    public function testTagsRemoveChange(): void
    {
        $startTag = Tag::factory()->create();
        $link = Link::factory()->create();

        $link->tags()->sync($startTag->id);

        $this->post('links/1', [
            '_method' => 'patch',
            'link_id' => $link->id,
            'url' => $link->url,
            'title' => $link->title,
            'description' => $link->description,
            'lists' => null,
            'tags' => null,
            'is_private' => $link->is_private,
        ]);

        $historyEntry = $link->revisionHistory()->latest()->first();

        $output = (new HistoryEntry($historyEntry))->render();

        $this->assertStringContainsString(
            sprintf('Removed <code>%s</code> from Tags', $startTag->name),
            $output
        );
    }

    public function testLinksAddedChange(): void
    {
        $link = Link::factory()->create();

        $this->post('links/1', [
            '_method' => 'patch',
            'link_id' => $link->id,
            'url' => $link->url,
            'title' => $link->title,
            'description' => $link->description,
            'lists' => 'New List,Example List',
            'tags' => null,
            'is_private' => $link->is_private,
        ]);

        $historyEntry = $link->revisionHistory()->latest()->first();

        $output = (new HistoryEntry($historyEntry))->render();

        $this->assertStringContainsString('Added <code>Example List, New List</code> to Lists', $output);
    }

    public function testLinksChange(): void
    {
        $startList = LinkList::factory()->create();
        $link = Link::factory()->create();

        $link->lists()->sync($startList->id);

        $this->post('links/1', [
            '_method' => 'patch',
            'link_id' => $link->id,
            'url' => $link->url,
            'title' => $link->title,
            'description' => $link->description,
            'lists' => 'New List,Example List',
            'tags' => null,
            'is_private' => $link->is_private,
        ]);

        $historyEntry = $link->revisionHistory()->latest()->first();

        $output = (new HistoryEntry($historyEntry))->render();

        $this->assertStringContainsString(
            sprintf('Changed Lists from <code>%s</code> to <code>Example List, New List</code>', $startList->name),
            $output
        );
    }

    public function testLinksRemoveChange(): void
    {
        $startList = LinkList::factory()->create();
        $link = Link::factory()->create();

        $link->lists()->sync($startList->id);

        $this->post('links/1', [
            '_method' => 'patch',
            'link_id' => $link->id,
            'url' => $link->url,
            'title' => $link->title,
            'description' => $link->description,
            'lists' => null,
            'tags' => null,
            'is_private' => $link->is_private,
        ]);

        $historyEntry = $link->revisionHistory()->latest()->first();

        $output = (new HistoryEntry($historyEntry))->render();

        $this->assertStringContainsString(
            sprintf('Removed <code>%s</code> from Lists', $startList->name),
            $output
        );
    }

    public function testIsPrivateChange(): void
    {
        $link = Link::factory()->create([
            'is_private' => '1',
        ]);

        $link->is_private = '0';
        $link->save();

        $historyEntry = $link->revisionHistory()->first();

        $output = (new HistoryEntry($historyEntry))->render();

        $this->assertStringContainsString(
            'Changed Private Status from <code>Yes</code> to <code>No</code>',
            $output
        );
    }
}
