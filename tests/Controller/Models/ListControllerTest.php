<?php

namespace Tests\Controller\Models;

use App\Enums\ModelAttribute;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Controller\Traits\PreparesTestData;
use Tests\TestCase;

class ListControllerTest extends TestCase
{
    use RefreshDatabase;
    use PreparesTestData;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);
    }

    public function test_index_view(): void
    {
        $this->createTestLists();

        $this->get('lists')
            ->assertOk()
            ->assertSee('Public List')
            ->assertSee('Internal List')
            ->assertDontSee('Private List');

        $this->flushSession();
        $this->get('lists?orderBy=created_at&orderDir=desc')
            ->assertOk()
            ->assertSeeInOrder([
                'Internal List',
                'Public List',
            ]);

        $this->flushSession();
        $this->get('lists?orderBy=created_at&orderDir=wrong-desc')
            ->assertOk()
            ->assertSeeInOrder([
                'Public List',
                'Internal List',
            ]);
    }

    public function test_index_view_with_sorting(): void
    {
        $list1 = LinkList::factory()->create([
            'name' => 'Test List',
            'user_id' => $this->user->id,
        ]);
        $list2 = LinkList::factory()->create([
            'name' => 'Example List',
            'user_id' => $this->user->id,
        ]);

        $linksGroups = Link::factory()->count(5)->create()->split(2);
        $linksGroups[0]->each(fn($link) => $link->lists()->attach([$list1->id]));
        $linksGroups[1]->each(fn($link) => $link->lists()->attach([$list2->id]));

        $this->get('lists?orderBy=links_count&orderDir=desc')
            ->assertOk()
            ->assertSeeInOrder([
                'Test List',
                'Example List',
            ]);

        $this->get('lists?orderBy=links_count&orderDir=asc')
            ->assertOk()
            ->assertSeeInOrder([
                'Example List',
                'Test List',
            ]);
    }

    public function test_index_view_with_valid_filter_result(): void
    {
        LinkList::factory()->create([
            'name' => 'Test List',
            'user_id' => $this->user->id,
        ]);

        $this->get('lists?filter=Test')
            ->assertOk()
            ->assertSee('Test List')
            ->assertDontSee('No Tags found');
    }

    public function test_index_view_with_no_filter_result(): void
    {
        LinkList::factory()->create([
            'name' => 'Test List',
            'user_id' => $this->user->id,
        ]);

        $this->get('lists?filter=asdfasdfasdf')
            ->assertOk()
            ->assertSee('No Lists found');
    }

    public function test_create_view(): void
    {
        $this->get('lists/create')
            ->assertOk()
            ->assertSee('Add List');
    }

    public function test_minimal_store_request(): void
    {
        $response = $this->post('lists', [
            'name' => 'Test List',
            'visibility' => 1,
        ]);

        $response->assertRedirect('lists/1');

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
    }

    public function test_full_store_request(): void
    {
        $response = $this->post('lists', [
            'name' => 'Test List',
            'description' => 'My custom description',
            'visibility' => 1,
        ]);

        $response->assertRedirect('lists/1');

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
        $this->assertEquals('My custom description', $databaseList->description);
    }

    public function test_store_request_with_continue(): void
    {
        $response = $this->post('lists', [
            'name' => 'Test List',
            'visibility' => 1,
            'reload_view' => '1',
        ]);

        $response->assertRedirect('lists/create');

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
    }

    public function test_validation_error_for_create(): void
    {
        $this->post('lists', [
            'name' => null,
            'visibility' => 1,
        ])->assertSessionHasErrors([
            'name',
        ]);
    }

    public function test_detail_view(): void
    {
        $otherUser = User::factory()->create();

        [$list, $list2, $list3, $firstUser] = $this->createTestLists();

        Link::factory()->for($firstUser)->create(['title' => 'FirstTestLink'])->lists()->sync([$list->id]);
        Link::factory()->for($firstUser)->create([
            'title' => 'InternalTestLink',
            'visibility' => ModelAttribute::VISIBILITY_INTERNAL,
        ])->lists()->sync([$list->id]);

        $this->actingAs($otherUser);
        $this->get('lists/1')->assertOk()->assertSee('Public List')->assertSee('Public List')
            ->assertSee('FirstTestLink')
            ->assertSee('InternalTestLink');
        $this->get('lists/2')->assertOk()->assertSee('Internal List')->assertSee('Internal List');
        $this->get('lists/3')->assertForbidden();
    }

    public function test_private_detail_view(): void
    {
        $list = LinkList::factory()->create(['visibility' => 3]);

        $response = $this->get('lists/1');

        $response->assertOk()
            ->assertSee('Private List')
            ->assertSee($list->name);
    }

    public function test_edit_view(): void
    {
        $this->createTestLists();

        $this->get('lists/1/edit')->assertOk()->assertSee('Public List')->assertSee('Edit List');
        $this->get('lists/2/edit')->assertOk()->assertSee('Internal List')->assertSee('Edit List');
        $this->get('lists/3/edit')->assertForbidden();
    }

    public function test_invalid_edit_request(): void
    {
        $this->get('lists/1/edit')->assertNotFound();
    }

    public function test_update_response(): void
    {
        $this->createTestLists();
        $list = LinkList::find(1);

        $this->patch('lists/1', [
            'list_id' => 1,
            'name' => 'New Public List',
            'visibility' => 1,
        ])->assertRedirect('lists/1');

        $this->assertEquals('New Public List', $list->refresh()->name);

        // Test other lists
        $this->patch('lists/2', [
            'list_id' => 2,
            'name' => 'New Internal List',
            'visibility' => 1,
        ])->assertRedirect('lists/2');

        $this->patch('lists/3', [
            'list_id' => $list->id,
            'name' => 'New Test List',
            'visibility' => 1,
        ])->assertForbidden();
    }

    public function test_missing_model_error_for_update(): void
    {
        $this->patch('lists/1', [
            'list_id' => '1',
            'name' => 'New Test List',
            'visibility' => 1,
        ])->assertNotFound();
    }

    public function test_unique_property_validation(): void
    {
        LinkList::factory()->create([
            'name' => 'Taken List Name',
            'user_id' => $this->user->id,
        ]);

        $baseList = LinkList::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->patch('lists/2', [
            'list_id' => $baseList->id,
            'name' => 'Taken List Name',
            'visibility' => 1,
        ])->assertSessionHasErrors(['name']);
    }

    public function test_validation_error_for_update(): void
    {
        $baseList = LinkList::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->patch('lists/1', [
            'list_id' => $baseList->id,
            //'name' => 'New Test List',
            'visibility' => 1,
        ])->assertSessionHasErrors(['name']);
    }

    public function test_delete_response(): void
    {
        $this->createTestLists();

        $this->assertEquals(3, LinkList::count());

        $this->deleteJson('lists/1')->assertRedirect();
        $this->deleteJson('lists/2')->assertForbidden();
        $this->deleteJson('lists/3')->assertForbidden();

        $this->assertEquals(2, LinkList::count());
    }

    public function test_missing_model_error_for_delete(): void
    {
        $this->delete('lists/1')->assertNotFound();
    }
}
