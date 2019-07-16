<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryUpdateTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @var User */
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testValidCategoryUpdate()
    {
        $this->be($this->user);

        $category = factory(Category::class)->create();

        $changed_data = [
            'name' => 'Changed Category Title',
        ];

        $updated_category = CategoryRepository::update($category, $changed_data);

        $asserted_data = array_merge($category->toArray(), $changed_data);

        $this->assertDatabaseHas('categories', $asserted_data);
    }
}
