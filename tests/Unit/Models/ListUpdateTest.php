<?php

namespace Tests\Unit\Models;

use App\Models\LinkList;
use App\Models\User;
use App\Repositories\ListRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ListUpdateTest extends TestCase
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
    public function testValidListUpdate(): void
    {
        $this->be($this->user);

        $list = factory(LinkList::class)->create();

        $changed_data = [
            'name' => 'Changed List Title',
        ];

        $updated_list = ListRepository::update($list, $changed_data);

        $asserted_data = array_merge($list->toArray(), $changed_data);

        $this->assertDatabaseHas('lists', $asserted_data);
    }
}
