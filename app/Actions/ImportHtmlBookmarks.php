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
    protected int $imported = 0;
    protected int $skipped = 0;

    /**
     * Import all links from a given bookmarks file.
     *
     * @param string $data
     * @param string $userId
     * @param bool $generateMeta
     * @return bool
     */
    public function run(string $data, string $userId, bool $generateMeta = true): bool
    {
        $parser = new NetscapeBookmarkParser(logger: Log::getLogger());

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
            if (Link::whereUrl($link['url'])->first()) {
                $this->skipped++;
                continue;
            }

            if ($generateMeta) {
                $linkMeta = (new HtmlMeta)->getFromUrl($link['url']);
                $title = $link['name'] ?: $linkMeta['title'];
                $description = $link['description'] ?: $linkMeta['description'];
            } else {
                $title = $link['name'];
                $description = $link['description'];
            }

            $newLink = new Link([
                'user_id' => $userId,
                'url' => $link['url'],
                'title' => $title,
                'description' => $description,
                'icon' => LinkIconMapper::mapLink($link['url']),
                'is_private' => usersettings('tags_private_default') === '1' ? true : $link['public'],
            ]);
            $newLink->created_at = Carbon::createFromTimestamp($link['dateCreated']);
            $newLink->updated_at = Carbon::now();
            $newLink->timestamps = false;
            $newLink->save();

            if (!empty($link['tags'])) {
                $newTags = [];
                foreach ($link['tags'] as $tag) {
                    $newTag = Tag::firstOrCreate([
                        'user_id' => $userId,
                        'name' => $tag,
                        'is_private' => usersettings('tags_private_default') === '1',
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
