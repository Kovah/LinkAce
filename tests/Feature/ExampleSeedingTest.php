<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class ExampleSeedingTest
 *
 * @package Tests\Feature
 */
class ExampleSeedingTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed('ExampleSeeder');
    }

    /**
     * Check if the example seeder has generated the correct amounts of entities
     *
     * @return void
     */
    public function testSeedingResults(): void
    {
        $this->assertEquals(1, User::count());
        $this->assertEquals(20, Category::count());
        $this->assertEquals(30, Tag::count());
        $this->assertEquals(50, Link::count());
    }
}
