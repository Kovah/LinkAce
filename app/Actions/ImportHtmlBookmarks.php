<?php

namespace App\Actions;

use App\Helper\HtmlMeta;
use App\Helper\LinkIconMapper;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Shaarli\NetscapeBookmarkParser\NetscapeBookmarkParser;

class ImportHtmlBookmarks
{
    protected int $imported = 0;
    protected int $skipped = 0;

    public function run(string $data, string $userId, bool $generateMeta = true): bool
    {
        $parser = new NetscapeBookmarkParser(
            defaultPub: usersettings('links_private_default'),
            logDir: storage_path('logs')
        );

        try {
            $links = $parser->parseString($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

        foreach ($links as $link) {
            if (Link::whereUrl($link['uri'])->first()) {
                $this->skipped++;
                continue;
            }

            if ($generateMeta) {
                $linkMeta = (new HtmlMeta)->getFromUrl($link['uri']);
                $title = $link['title'] ?: $linkMeta['title'];
                $description = $link['note'] ?: $linkMeta['description'];
            } else {
                $title = $link['title'];
                $description = $link['note'];
            }

            $newLink = new Link([
                'user_id' => $userId,
                'url' => $link['uri'],
                'title' => $title,
                'description' => $description,
                'icon' => LinkIconMapper::getIconForUrl($link['uri']),
                'is_private' => $link['pub']
            ]);
            $newLink->created_at = Carbon::createFromTimestamp($link['time']);
            $newLink->updated_at = Carbon::now();
            $newLink->timestamps = false;
            $newLink->save();

            if (!empty($link['tags'])) {
                $newTags = [];
                foreach ($link['tags'] as $tag) {
                    $newTag = Tag::firstOrCreate([
                        'user_id' => $userId,
                        'name' => $tag,
                    ]);
                    $newTags[] = $newTag->id;
                }

                $newLink->tags()->sync($newTags);
            }

            $this->imported++;
        }

        return true;
    }

    public function getImportCount(): int
    {
        return $this->imported;
    }

    public function getSkippedCount(): int
    {
        return $this->skipped;
    }
}
