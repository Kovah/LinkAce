<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\LinkList::class, function (Faker $faker) {

    $user = \App\Models\User::first();

    if (empty($user)) {
        throw new Exception('Users need to be generated prior to generating categories.');
    }

    return [
        'user_id' => $user->id,
        'name' => ucwords($faker->words(random_int(1, 2), true)),
        'description' => random_int(0, 1) ? $faker->sentences(random_int(1, 2), true) : null,
        'is_private' => $faker->boolean(10),
    ];
});
