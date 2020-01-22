<?php

use Illuminate\Database\Seeder;

class ExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate users, categories and tags
        factory(\App\Models\User::class, 1)->create();
        $lists = factory(\App\Models\LinkList::class, 10)->create();
        $tags = factory(\App\Models\Tag::class, 30)->create();

        // Generate links and attach tags to them
        factory(\App\Models\Link::class, 50)->create()->each(function (\App\Models\Link $link) use ($tags, $lists) {
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
