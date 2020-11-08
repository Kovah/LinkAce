<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => '$2y$10$9.preebMjZ.8obdvk5ZVdOCw7Cq1EJm6i1B1RJevxCXYW0lUiwDJG', // secretpassword
            'api_token' => Str::random(32),
        ];
    }
}
