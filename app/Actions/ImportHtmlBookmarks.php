<?php

namespace App\Actions;

use App\Enums\ModelAttribute;
use App\Jobs\ImportLinkJob;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Shaarli\NetscapeBookmarkParser\NetscapeBookmarkParser;

class ImportHtmlBookmarks
{
    protected int $queued = 0;
    protected int $skipped = 0;
    protected ?Tag $importTag = null;

    public function run(string $data, string $userId, bool $generateMeta = true): bool
    {
        $parser = new NetscapeBookmarkParser(logger: Log::channel('import'));

        try {
            $links = $parser->parseString($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

        $this->importTag = Tag::firstOrCreate([
            'user_id' => $userId,
            'name' => 'import-' . now()->format('YmdHis'),
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        $knownUrls = Link::query()->byUser($userId)->pluck('url')->flip()->all();

        foreach ($links as $i => $link) {
            $rawUrl = $link['url'];
            $link = $this->decodeEntities($link);

            if (!Str::isUrl($link['url'], ['http', 'https'])) {
                // skip any links that are not a valid http(s) URL
                $this->skipped++;
                continue;
            }

            // Links imported before entities were decoded are stored with `&amp;` in the URL
            if (isset($knownUrls[$link['url']]) || isset($knownUrls[$rawUrl])) {
                $this->skipped++;
                continue;
            }

            $knownUrls[$link['url']] = true;

            dispatch(new ImportLinkJob($userId, $link, $this->importTag, $generateMeta))->delay($i * 10);

            $this->queued++;
        }

        return true;
    }

    /**
     * The parser keeps entities as written: browsers export `&` in URLs and titles as `&amp;`
     */
    protected function decodeEntities(array $link): array
    {
        $decode = fn (?string $value) => $value === null
            ? null
            : html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $link['url'] = $decode($link['url']);
        $link['name'] = $decode($link['name']);
        $link['description'] = $decode($link['description']);
        $link['tags'] = array_map($decode, $link['tags']);

        return $link;
    }

    public function getQueuedCount(): int
    {
        return $this->queued;
    }

    public function getSkippedCount(): int
    {
        return $this->skipped;
    }

    public function getImportTag(): ?Tag
    {
        return $this->importTag;
    }
}
