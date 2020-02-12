<?php

namespace Tests\Feature\Controller\Models;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ListControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->actingAs($this->user);
    }

    public function testValidListOverviewResponse(): void
    {
        $list = factory(LinkList::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('lists');

        $response->assertStatus(200)
            ->assertSee($list->name);
    }

    public function testValidListCreateResponse(): void
    {
        $response = $this->get('lists/create');

        $response->assertStatus(200)
            ->assertSee('Add List');
    }

    public function testValidListMinimalStoreResponse(): void
    {
        $response = $this->post('lists', [
            'name' => 'Test List',
            'is_private' => '0',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('lists/1');

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
    }

    public function testValidListFullStoreResponse(): void
    {
        $response = $this->post('lists', [
            'name' => 'Test List',
            'description' => 'My custom description',
            'is_private' => '1',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('lists/1');

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
        $this->assertEquals('My custom description', $databaseList->description);
    }

    public function testValidListStoreResponseWithContinue(): void
    {
        $response = $this->post('lists', [
            'name' => 'Test List',
            'is_private' => '1',
            'reload_view' => '1',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('lists/create');

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
    }

    public function testInvalidListStoreResponse(): void
    {
        $response = $this->post('lists', [
            'name' => null,
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testValidListDetailResponse(): void
    {
        $list = factory(LinkList::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('lists/1');

        $response->assertStatus(200)
            ->assertSee($list->name);
    }

    public function testValidListEditResponse(): void
    {
        factory(LinkList::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('lists/1/edit');

        $response->assertStatus(200)
            ->assertSee('Edit List');
    }

    public function testInvalidListEditResponse(): void
    {
        $response = $this->get('lists/1/edit');

        $response->assertStatus(404);
    }

    public function testValidListUpdateResponse(): void
    {
        $baseList = factory(LinkList::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->post('lists/1', [
            '_method' => 'patch',
            'list_id' => $baseList->id,
            'name' => 'New Test List',
            'is_private' => '0',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('lists/1');

        $updatedLink = $baseList->fresh();

        $this->assertEquals('New Test List', $updatedLink->name);
    }

    public function testInvalidLinkUpdateResponse(): void
    {
        $baseList = factory(LinkList::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->post('lists/1', [
            '_method' => 'patch',
            'list_id' => $baseList->id,
            //'name' => 'New Test List',
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testValidListDeleteResponse(): void
    {
        factory(LinkList::class)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->post('lists/1', [
            '_method' => 'delete',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('lists');

        $databaseList = LinkList::withTrashed()->first();

        $this->assertNotNull($databaseList->deleted_at);
        $this->assertNotNull($databaseList->deleted_at);
    }

    public function testInvalidListDeleteResponse(): void
    {
        $response = $this->post('lists/1', [
            '_method' => 'delete',
        ]);

        $response->assertStatus(404);
    }
}
