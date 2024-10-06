<?php

namespace App\Jobs;

use App\Enums\ModelAttribute;
use App\Helper\HtmlMeta;
use App\Helper\LinkIconMapper;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ImportLinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $userId,
        public array $link,
        public Tag $importTag,
        public bool $generateMeta
    ) {
        $this->onQueue('import');
    }

    public function handle(): void
    {
        if ($this->generateMeta) {
            $linkMeta = (new HtmlMeta)->getFromUrl($this->link['url']);
            $title = $this->link['name'] ?: $linkMeta['title'];
            $description = $this->link['description'] ?: $linkMeta['description'];
        } else {
            $title = $this->link['name'];
            $description = $this->link['description'];
        }

        if (isset($this->link['public'])) {
            $visibility = $this->link['public'] ? ModelAttribute::VISIBILITY_PUBLIC : ModelAttribute::VISIBILITY_PRIVATE;
        } else {
            $visibility = usersettings('links_default_visibility', $this->userId);
        }

        $newLink = new Link([
            'user_id' => $this->userId,
            'url' => $this->link['url'],
            'title' => $title,
            'description' => $description,
            'icon' => LinkIconMapper::getIconForUrl($this->link['url']),
            'visibility' => $visibility,
        ]);

        $newLink->created_at = $this->link['dateCreated']
            ? Carbon::createFromTimestamp($this->link['dateCreated'])
            : Carbon::now();
        $newLink->updated_at = Carbon::now();
        $newLink->timestamps = false;
        $newLink->save();

        $newTags = [$this->importTag->id];

        if (!empty($this->link['tags'])) {
            foreach ($this->link['tags'] as $tag) {
                $newTag = Tag::firstOrCreate([
                    'user_id' => $this->userId,
                    'name' => $tag,
                    'visibility' => usersettings('tags_default_visibility', $this->userId),
                ]);
                $newTags[] = $newTag->id;
            }
        }

        $newLink->tags()->sync($newTags);
    }
}
