<?php

namespace Tests\Controller\Models;

use App\Models\LinkList;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);
    }

    public function testIndexView(): void
    {
        $list = LinkList::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('lists');

        $response->assertOk()
            ->assertSee($list->name);
    }

    public function testCreateView(): void
    {
        $response = $this->get('lists/create');

        $response->assertOk()
            ->assertSee('Add List');
    }

    public function testMinimalStoreRequest(): void
    {
        $response = $this->post('lists', [
            'name' => 'Test List',
            'is_private' => '0',
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
            'is_private' => '1',
        ]);

        $response->assertRedirect('lists/1');

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
        $this->assertEquals('My custom description', $databaseList->description);
    }

    public function testStoreRequestWithPrivateDefault(): void
    {
        Setting::create([
            'user_id' => 1,
            'key' => 'lists_private_default',
            'value' => '1',
        ]);

        $response = $this->post('lists', [
            'name' => 'Test List',
            'is_private' => usersettings('lists_private_default'),
        ]);

        $response->assertRedirect('lists/1');

        $databaseList = LinkList::first();

        $this->assertTrue($databaseList->is_private);
    }

    public function testStoreRequestWithContinue(): void
    {
        $response = $this->post('lists', [
            'name' => 'Test List',
            'is_private' => '1',
            'reload_view' => '1',
        ]);

        $response->assertRedirect('lists/create');

        $databaseList = LinkList::first();

        $this->assertEquals('Test List', $databaseList->name);
    }

    public function testValidationErrorForCreate(): void
    {
        $response = $this->post('lists', [
            'name' => null,
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testDetailView(): void
    {
        $list = LinkList::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('lists/1');

        $response->assertOk()
            ->assertSee($list->name);
    }

    public function testEditView(): void
    {
        LinkList::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get('lists/1/edit');

        $response->assertOk()
            ->assertSee('Edit List')
            ->assertSee('Update List');
    }

    public function testInvalidEditRequest(): void
    {
        $response = $this->get('lists/1/edit');

        $response->assertNotFound();
    }

    public function testUpdateResponse(): void
    {
        $baseList = LinkList::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->patch('lists/1', [
            'list_id' => $baseList->id,
            'name' => 'New Test List',
            'is_private' => '0',
        ]);

        $response->assertRedirect('lists/1');

        $updatedLink = $baseList->fresh();

        $this->assertEquals('New Test List', $updatedLink->name);
    }

    public function testMissingModelErrorForUpdate(): void
    {
        $response = $this->patch('lists/1', [
            'list_id' => '1',
            'name' => 'New Test List',
            'is_private' => '0',
        ]);

        $response->assertNotFound();
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

        $response = $this->patch('lists/2', [
            'list_id' => $baseList->id,
            'name' => 'Taken List Name',
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testValidationErrorForUpdate(): void
    {
        $baseList = LinkList::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->patch('lists/1', [
            'list_id' => $baseList->id,
            //'name' => 'New Test List',
            'is_private' => '0',
        ]);

        $response->assertSessionHasErrors([
            'name',
        ]);
    }

    public function testDeleteResponse(): void
    {
        LinkList::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->deleteJson('lists/1');

        $response->assertRedirect('lists');

        $databaseList = LinkList::withTrashed()->first();

        $this->assertNotNull($databaseList->deleted_at);
        $this->assertNotNull($databaseList->deleted_at);
    }

    public function testMissingModelErrorForDelete(): void
    {
        $response = $this->delete('lists/1');

        $response->assertNotFound();
    }
}
