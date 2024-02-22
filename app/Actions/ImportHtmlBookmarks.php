<?php

namespace App\Actions;

use App\Enums\ModelAttribute;
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

            if (isset($link['public'])) {
                $visibility = $link['public'] ? ModelAttribute::VISIBILITY_PUBLIC : ModelAttribute::VISIBILITY_PRIVATE;
            } else {
                $visibility = usersettings('links_default_visibility');
            }
            $newLink = new Link([
                'user_id' => $userId,
                'url' => $link['url'],
                'title' => $title,
                'description' => $description,
                'icon' => LinkIconMapper::getIconForUrl($link['url']),
                'visibility' => $visibility,
            ]);
            $newLink->created_at = $link['dateCreated']
                ? Carbon::createFromTimestamp($link['dateCreated'])
                : Carbon::now();
            $newLink->updated_at = Carbon::now();
            $newLink->timestamps = false;
            $newLink->save();

            $newTags = [$this->importTag->id];
            if (!empty($link['tags'])) {
                foreach ($link['tags'] as $tag) {
                    $newTag = Tag::firstOrCreate([
                        'user_id' => $userId,
                        'name' => $tag,
                        'visibility' => usersettings('tags_default_visibility'),
                    ]);
                    $newTags[] = $newTag->id;
                }
            }
            $newLink->tags()->sync($newTags);

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

    public function getImportTag(): ?Tag
    {
        return $this->importTag;
    }
}
