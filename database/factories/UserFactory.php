<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$9.preebMjZ.8obdvk5ZVdOCw7Cq1EJm6i1B1RJevxCXYW0lUiwDJG', // secretpassword
        'api_token' => Str::random(32),
    ];
});
