<?php

namespace Database\Factories;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
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
            'link_id' => Link::first()->id ?? Link::factory(),
            'note' => $this->faker->sentences(random_int(1, 5), true),
            'visibility' => ModelAttribute::VISIBILITY_PUBLIC,
        ];
    }
}
