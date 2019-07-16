<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryDeleteTest extends TestCase
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

        $category = factory(Category::class)->create();

        $deletion_result = CategoryRepository::delete($category);

        $this->assertTrue($deletion_result);
    }
}
