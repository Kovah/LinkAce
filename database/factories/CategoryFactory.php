<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {

    $user = \App\Models\User::first();

    if (empty($user)) {
        throw new Exception('Users need to be generated prior to generating categories.');
    }

    // Select a parent category with a propability of 30%
    $has_parent = $faker->boolean(30);

    if ($has_parent && \App\Models\Category::parentOnly()->count() > 0) {
        $parent_category = \App\Models\Category::parentOnly()->inRandomOrder()->first()->id;
    }

    return [
        'user_id' => $user->id,
        'name' => ucwords($faker->words(random_int(1, 2), true)),
        'description' => random_int(0, 1) ? $faker->sentences(random_int(1, 2), true) : null,
        'parent_category' => $parent_category ?? null,
        'is_private' => $faker->boolean(10),
    ];
});
