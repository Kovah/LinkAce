<?php

namespace Tests\Feature;

use App\Http\Controllers\CategoryController;
use App\Http\Requests\CategoryStoreRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryCreationTest extends TestCase
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
    public function testValidCategoryCreationRequest()
    {
        $data = [
            '_token' => csrf_token(),
            'name' => 'Test Category Number 1',
            'description' => 'Ambitioni dedisse scripsisse iudicaretur',
            'parent_category' => null,
            'is_private' => false,
        ];

        // Create a new request for the category controller
        // @FIXME This request works but does not use an authenticated user, so the user ID is not available in the controller...
        $request = new CategoryStoreRequest($data);
        $controller = new CategoryController();
        $response = $controller->store($request);

        // Assert that a redirect is performed
        $this->assertEquals(302, $response->getStatusCode());

        // Assert that the database now has a category called 'Test Category Number 1'
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category Number 1',
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInvalidCategoryCreationRequest()
    {
        $data = [
            '_token' => csrf_token(),
            'name' => null,
            'description' => null,
            'parent_category' => null,
            'is_private' => false,
        ];

        // Create a new request for the category controller
        // @FIXME This approach returns a 422 error (Unprocessable Entity)?
        $response = $this->actingAs($this->user)
            ->call('POST', route('categories.store'), $data);

        // Assert that a redirect is performed
        $this->assertEquals(302, $response->getStatusCode());
    }
}
