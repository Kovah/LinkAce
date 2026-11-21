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

        foreach ($links as $i => $link) {
            // The parser keeps entities as written: browsers export `&` in URLs and titles as `&amp;`
            foreach (['url', 'name', 'description'] as $field) {
                if (is_string($link[$field] ?? null)) {
                    $link[$field] = html_entity_decode($link[$field], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }
            }

            if (!Str::isUrl($link['url'], ['http', 'https'])) {
                // skip any links that are not a valid http(s) URL
                $this->skipped++;
                continue;
            }

            if (Link::query()->byUser($userId)->whereUrl($link['url'])->first()) {
                $this->skipped++;
                continue;
            }

            dispatch(new ImportLinkJob($userId, $link, $this->importTag, $generateMeta))->delay($i * 10);

            $this->queued++;
        }

        return true;
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
