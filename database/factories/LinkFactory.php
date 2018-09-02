<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Link::class, function (Faker $faker) {

    $user = \App\Models\User::first();

    if (empty($user)) {
        throw new Exception('Users need to be generated prior to generating links.');
    }

    // Select a category with a propability of 70%
    $has_category = $faker->boolean(70);
    if ($has_category && \App\Models\Category::count() > 0) {
        $category = \App\Models\Category::inRandomOrder()->first()->id;
    }

    return [
        'user_id' => $user->id,
        'category_id' => $category ?? null,
        'url' => $faker->url,
        'title' => $faker->boolean(70) ? $faker->words(random_int(1, 5), true) : $faker->domainName,
        'description' => $faker->boolean(70) ? $faker->sentences(random_int(1, 3), true) : null,
        'is_private' => $faker->boolean(10),
    ];
});
