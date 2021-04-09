<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\Note;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

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
            'link_id' => Link::first()->id ?? Link::factory(),
            'note' => $this->faker->sentences(random_int(1, 5), true),
            'is_private' => $this->faker->boolean(10),
        ];
    }
}
