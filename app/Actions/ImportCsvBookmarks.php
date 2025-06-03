<?php

namespace App\Actions;

use App\Enums\ModelAttribute;
use App\Jobs\ImportLinkJob;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

/**
 * CSV import based on Pocket exports (https://getpocket.com).
 */
class ImportCsvBookmarks
{
    protected int $queued = 0;
    protected int $skipped = 0;
    protected ?Tag $importTag = null;

    public function run(string $data, string $userId, bool $generateMeta = true): bool
    {
        try {
            $reader = Reader::createFromString($data);
            $records = $reader->getRecords();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

        $this->importTag = Tag::firstOrCreate([
            'user_id' => $userId,
            'name' => 'import-' . now()->format('YmdHis'),
            'visibility' => ModelAttribute::VISIBILITY_PRIVATE,
        ]);

        foreach ($records as $i => $record) {
            // map Pocket's export columns title, url, time_added, tags, status
            // to Linkace's default bookmark structure
            [$name, $url, $dateCreated, $tags, $status] = $record;

            if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                // skip any links that are not a valid URL
                $this->skipped++;
                continue;
            }

            if (Link::whereUrl($url)->first()) {
                $this->skipped++;
                continue;
            }

            if ($name === $url) {
                // ignore names that equal the URL
                $name = '';
            } else {
                // normalize white-space
                $name = preg_replace('/\s+/', ' ', $name);
                $name = trim($name);
            }

            if (!empty($tags)) {
                // Pocket exports join tags with pipes
                $tags = explode('|', $tags);
            }

            // there is no description included in Pocket export but expected by Linkace
            $description = '';

            // build link array
            $link = compact('url', 'name', 'description', 'dateCreated', 'tags');

            dispatch(new ImportLinkJob($userId, $link, $this->importTag, $generateMeta))->delay($i);

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
