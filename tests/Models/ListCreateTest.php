<?php

namespace Tests\Models;

use App\Models\User;
use App\Repositories\ListRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ListCreateTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @var User */
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testValidListCreation(): void
    {
        $this->be($this->user);

        $originalData = [
            'name' => 'Test List',
            'description' => 'Test description',
        ];

        $list = ListRepository::create($originalData);

        $automatedData = [
            'id' => $list->id,
            'user_id' => auth()->user()->id,
            'created_at' => $list->created_at,
            'updated_at' => $list->updated_at,
            'deleted_at' => null,
        ];

        $assertedData = array_merge($automatedData, $originalData);

        $this->assertDatabaseHas('lists', $assertedData);
    }
}
