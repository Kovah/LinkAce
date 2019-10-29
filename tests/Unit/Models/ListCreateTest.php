<?php

namespace Tests\Unit\Models;

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

        $this->user = factory(User::class)->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testValidListCreation(): void
    {
        $this->be($this->user);

        $original_data = [
            'name' => 'Test List',
            'description' => 'Test description',
        ];

        $list = ListRepository::create($original_data);

        $automated_data = [
            'id' => $list->id,
            'user_id' => auth()->user()->id,
            'created_at' => $list->created_at,
            'updated_at' => $list->updated_at,
            'deleted_at' => null,
        ];

        $asserted_data = array_merge($automated_data, $original_data);

        $this->assertDatabaseHas('lists', $asserted_data);
    }
}
