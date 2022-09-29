<?php

namespace Database\Factories;

use App\Enums\ModelAttribute;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
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
            'name' => $this->faker->words(random_int(2, 3), true),
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ];
    }
}
