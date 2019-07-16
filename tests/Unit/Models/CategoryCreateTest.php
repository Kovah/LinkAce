<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryCreateTest extends TestCase
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
    public function testValidCategoryCreation(): void
    {
        $this->be($this->user);

        $original_data = [
            'name' => 'Test Category',
            'description' => 'Test description',
        ];

        $category = CategoryRepository::create($original_data);

        $automated_data = [
            'id' => $category->id,
            'user_id' => auth()->user()->id,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
            'deleted_at' => null,
        ];

        $asserted_data = array_merge($automated_data, $original_data);

        $this->assertDatabaseHas('categories', $asserted_data);
    }
}
