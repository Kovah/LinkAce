<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\PrivateShare;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PrivateShareFactory extends Factory
{
    protected $model = PrivateShare::class;

    public function definition(): array
    {
        // Randomly select a model type
        $entityType = $this->faker->randomElement([Link::class, LinkList::class, Tag::class]);
        // Get a random instance of the selected model type
        $entity = $entityType::factory()->create();

        return [
            'ident' => Str::ulid(),
            'user_id' => User::factory()->create(),
            'entity_id' => $entity->id,
            'entity_type' => $entityType,
            'expires_at' => $this->faker->boolean() ? Carbon::now()->addWeek() : null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
