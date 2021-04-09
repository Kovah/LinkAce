<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'user_id' => User::first()->id ?? User::factory(),
            'name' => $this->faker->words(random_int(2, 3), true),
            'is_private' => $this->faker->boolean(10),
        ];
    }
}
