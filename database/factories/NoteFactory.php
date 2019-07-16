<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Note::class, function (Faker $faker) {

    $user = \App\Models\User::first();

    if (empty($user)) {
        throw new Exception('Users need to be generated prior to generating tags.');
    }

    return [
        'user_id' => $user->id,
        'link_id' => function () {
            return factory(App\Models\Link::class)->create()->id;
        },
        'note' => $faker->words(random_int(10, 30), true),
        'is_private' => $faker->boolean(10),
    ];
});
