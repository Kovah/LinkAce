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
        factory(\App\Models\Category::class, 10)->create();
        factory(\App\Models\Category::class, 10)->create(); // Generate some child categories
        $tags = factory(\App\Models\Tag::class, 30)->create();

        // Generate links and attach tags to them
        factory(\App\Models\Link::class, 50)->create()->each(function (\App\Models\Link $link) use ($tags) {
            if (random_int(0, 1)) {
                // Attach a random number of tags to the link
                $link->tags()->sync($tags->random(random_int(1, 30))->pluck('id'));
            }
        });
    }
}
