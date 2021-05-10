<?php

namespace App\Actions;

use App\Helper\HtmlMeta;
use App\Helper\LinkIconMapper;
use App\Models\Link;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Shaarli\NetscapeBookmarkParser\NetscapeBookmarkParser;

class ImportHtmlBookmarks
{
    protected $imported = 0;
    protected $skipped = 0;

    /**
     * Import all links from a given bookmarks file.
     *
     * @param string $data
     * @param string $userId
     * @param bool   $generateMeta
     * @return bool
     */
    public function run(string $data, string $userId, bool $generateMeta = true): bool
    {
        $parser = new NetscapeBookmarkParser(true, [], '0', storage_path('logs'));

        try {
            $links = $parser->parseString($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

        if (empty($links)) {
            // This will never be reached at the moment because the bookmark parser is not capable of handling
            // empty bookmarks exports. See https://github.com/shaarli/netscape-bookmark-parser/issues/50
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

            $newLink = Link::create([
                'user_id' => $userId,
                'url' => $link['uri'],
                'title' => $title,
                'description' => $description,
                'icon' => LinkIconMapper::mapLink($link['uri']),
                'is_private' => $link['pub'],
                'created_at' => Carbon::createFromTimestamp($link['time']),
                'updated_at' => Carbon::now(),
            ]);

            if (!empty($link['tags'])) {
                $tags = explode(' ', $link['tags']);

                $newTags = [];
                foreach ($tags as $tag) {
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
