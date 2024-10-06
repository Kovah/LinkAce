<?php

namespace App\Actions;

use App\Enums\ModelAttribute;
use App\Jobs\ImportLinkJob;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;
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
            if (Link::whereUrl($link['url'])->first()) {
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
