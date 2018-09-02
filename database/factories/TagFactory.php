<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Tag::class, function (Faker $faker) {

    $user = \App\Models\User::first();

    if (empty($user)) {
        throw new Exception('Users need to be generated prior to generating tags.');
    }

    return [
        'user_id' => $user->id,
        'name' => $faker->words(random_int(1, 3), true),
        'is_private' => $faker->boolean(10),
    ];
});
