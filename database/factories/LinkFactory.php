<?php

namespace Database\Factories;

use App\Enums\ModelAttribute;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
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
            'url' => $this->faker->url(),
            'title' => $this->faker->boolean(70)
                ? $this->faker->words(random_int(2, 5), true)
                : $this->faker->domainName(),
            'description' => $this->faker->boolean(70) ? $this->faker->sentences(random_int(1, 3), true) : null,
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
            'check_disabled' => false,
        ];
    }
}
