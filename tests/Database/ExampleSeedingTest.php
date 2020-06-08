<?php

namespace Tests\Database;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExampleSeedingTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed('ExampleSeeder');
    }

    public function testSeedingResults(): void
    {
        $this->assertEquals(1, User::count());
        $this->assertEquals(10, LinkList::count());
        $this->assertEquals(30, Tag::count());
        $this->assertEquals(50, Link::count());
    }
}
