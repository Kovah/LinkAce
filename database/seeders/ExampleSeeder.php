<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;

class ExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        // Generate users, categories and tags
        User::factory()->create();
        $lists = LinkList::factory()->count(10)->create();
        $tags = Tag::factory()->count(30)->create();

        // Generate links and attach tags to them
        Link::factory()->count(50)->create()->each(function (Link $link) use ($tags, $lists) {
            if (random_int(0, 1)) {
                // Attach a random number of tags to the link
                $link->tags()->sync($tags->random(random_int(1, 8))->pluck('id'));
            }

            if (random_int(0, 1)) {
                // Attach a random number of tags to the link
                $link->lists()->sync($lists->random(random_int(1, 2))->pluck('id'));
            }
        });
    }
}
