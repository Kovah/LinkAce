<?php

namespace Database\Factories;

use App\Enums\ModelAttribute;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'user_id' => User::notSystem()->first()->id ?? User::factory(),
            'name' => ucwords($this->faker->words(random_int(2, 5), true)),
            'description' => random_int(0, 1) ? $this->faker->sentences(random_int(1, 2), true) : null,
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ];
    }
}
