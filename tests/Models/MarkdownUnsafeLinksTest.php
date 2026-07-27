<?php

namespace Tests\Models;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\User;
use App\Settings\UserSettings;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Regression tests for GHSA-pm4x-ww2f-xwvp: Str::markdown() is called on
 * link/note/list descriptions without disabling CommonMark's unsafe links,
 * so a stored `javascript:` Markdown link is rendered with a live `href`
 * into raw Blade output ({!! !!}), producing stored XSS for any viewer
 * (including administrators) who clicks the link.
 */
class MarkdownUnsafeLinksTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

        UserSettings::fake([
            'markdown_for_text' => true,
        ]);
    }

    public function test_link_description_does_not_render_javascript_link_as_href(): void
    {
        $link = Link::factory()->create([
            'description' => '[Open link](javascript:alert(document.cookie))',
        ]);

        $this->assertStringNotContainsString('href="javascript:', $link->formatted_description);
        $this->assertStringContainsString('Open link', $link->formatted_description);
    }

    public function test_link_description_still_renders_https_link_as_href(): void
    {
        $link = Link::factory()->create([
            'description' => '[Open link](https://example.com)',
        ]);

        $this->assertStringContainsString('href="https://example.com"', $link->formatted_description);
    }

    public function test_note_does_not_render_javascript_link_as_href(): void
    {
        $link = Link::factory()->create();

        $note = Note::factory()->create([
            'link_id' => $link->id,
            'note' => '[Open link](javascript:alert(document.cookie))',
        ]);

        $this->assertStringNotContainsString('href="javascript:', $note->formatted_note);
        $this->assertStringContainsString('Open link', $note->formatted_note);
    }

    public function test_note_still_renders_https_link_as_href(): void
    {
        $link = Link::factory()->create();

        $note = Note::factory()->create([
            'link_id' => $link->id,
            'note' => '[Open link](https://example.com)',
        ]);

        $this->assertStringContainsString('href="https://example.com"', $note->formatted_note);
    }

    public function test_list_description_does_not_render_javascript_link_as_href(): void
    {
        $list = LinkList::factory()->create([
            'description' => '[Open link](javascript:alert(document.cookie))',
        ]);

        $this->assertStringNotContainsString('href="javascript:', $list->formatted_description);
        $this->assertStringContainsString('Open link', $list->formatted_description);
    }

    public function test_list_description_still_renders_https_link_as_href(): void
    {
        $list = LinkList::factory()->create([
            'description' => '[Open link](https://example.com)',
        ]);

        $this->assertStringContainsString('href="https://example.com"', $list->formatted_description);
    }
}
