<?php

namespace Database\Factories;

use App\Models\LinkList;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LinkList::class;

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
            'name' => ucwords($this->faker->words(random_int(2, 5), true)),
            'description' => random_int(0, 1) ? $this->faker->sentences(random_int(1, 2), true) : null,
            'is_private' => $this->faker->boolean(10),
        ];
    }
}
