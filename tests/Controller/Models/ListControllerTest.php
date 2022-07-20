<?php

namespace Tests\Controller\Models;

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

    public function testIndexView(): void
    {
        $this->createTestLists();

        $this->get('lists')
            ->assertOk()
            ->assertSee('Public List')
            ->assertSee('Internal List')
            ->assertDontSee('Private List');
    }

    public function testIndexViewWithValidFilterResult(): void
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

    public function testIndexViewWithNoFilterResult(): void
    {
        LinkList::factory()->create([
            'name' => 'Test List',
            'user_id' => $this->user->id,
        ]);

        $this->get('lists?filter=asdfasdfasdf')
            ->assertOk()
            ->assertSee('No Lists found');
    }

    public function testCreateView(): void
    {
        $this->get('lists/create')
            ->assertOk()
            ->assertSee('Add List');
    }

    public function testMinimalStoreRequest(): void
    {
        $response = $this->post('lists', [
            'name' => 'Test List',
            'visibility' => 1,
        ]);

        $response->assertRedirect('lists/1');

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
    }

    public function testFullStoreRequest(): void
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

    public function testStoreRequestWithContinue(): void
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

    public function testValidationErrorForCreate(): void
    {
        $this->post('lists', [
            'name' => null,
            'visibility' => 1,
        ])->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testDetailView(): void
    {
        $this->createTestLists();

        $this->get('lists/1')->assertOk()->assertSee('Public List')->assertSee('Public List');
        $this->get('lists/2')->assertOk()->assertSee('Internal List')->assertSee('Internal List');
        $this->get('lists/3')->assertForbidden();
    }

    public function testPrivateDetailView(): void
    {
        $list = LinkList::factory()->create(['visibility' => 3]);

        $response = $this->get('lists/1');

        $response->assertOk()
            ->assertSee('Private List')
            ->assertSee($list->name);
    }

    public function testEditView(): void
    {
        $this->createTestLists();

        $this->get('lists/1/edit')->assertOk()->assertSee('Public List')->assertSee('Edit List');
        $this->get('lists/2/edit')->assertOk()->assertSee('Internal List')->assertSee('Edit List');
        $this->get('lists/3/edit')->assertForbidden();
    }

    public function testInvalidEditRequest(): void
    {
        $this->get('lists/1/edit')->assertNotFound();
    }

    public function testUpdateResponse(): void
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

    public function testMissingModelErrorForUpdate(): void
    {
        $this->patch('lists/1', [
            'list_id' => '1',
            'name' => 'New Test List',
            'visibility' => 1,
        ])->assertNotFound();
    }

    public function testUniquePropertyValidation(): void
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

    public function testValidationErrorForUpdate(): void
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

    public function testDeleteResponse(): void
    {
        $this->createTestLists();

        $this->assertEquals(3, LinkList::count());

        $this->deleteJson('lists/1')->assertRedirect();
        $this->deleteJson('lists/2')->assertForbidden();
        $this->deleteJson('lists/3')->assertForbidden();

        $this->assertEquals(2, LinkList::count());
    }

    public function testMissingModelErrorForDelete(): void
    {
        $this->delete('lists/1')->assertNotFound();
    }
}
