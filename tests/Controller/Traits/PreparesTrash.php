<?php

namespace Tests\Controller\Traits;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;

trait PreparesTrash
{
    protected function setupTrashTestData(): void
    {
        $tagExample = Tag::create([
            'name' => 'Examples',
            'user_id' => $this->user->id,
        ]);

        $listTest = LinkList::create([
            'name' => 'A Tests List',
            'user_id' => $this->user->id,
        ]);

        $linkExample = Link::create([
            'user_id' => $this->user->id,
            'url' => 'https://example.com',
            'title' => 'Very special site title',
            'description' => 'Some description for this site',
            'is_private' => true,
        ]);

        $linkExample->tags()->attach($tagExample->id);

        $linkExampleNote = Note::create([
            'user_id' => $this->user->id,
            'link_id' => $linkExample->id,
            'note' => 'Quisque placerat facilisis egestas cillum dolore.',
            'is_private' => false,
        ]);

        $linkTest = Link::create([
            'user_id' => $this->user->id,
            'url' => 'https://test.com',
            'title' => 'Test Site',
            'description' => null,
            'is_private' => false,
        ]);

        $linkTest->lists()->attach($listTest->id);

        $tagExample->delete();
        $listTest->delete();
        $linkExample->delete();
        $linkExampleNote->delete();
        $linkTest->delete();
    }
}
