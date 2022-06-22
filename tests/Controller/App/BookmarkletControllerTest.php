<?php

namespace Tests\Controller\App;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookmarkletControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidBookmarkletResponse(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('bookmarklet/add?u=https://example.com&t=Example%20Title');

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertSee('Example Title');
    }

    public function testBookmarkletWithExistingLink(): void
    {
        Link::factory()->create([
            'url' => 'https://example.com/test',
        ]);

        $this->actingAs(User::first());

        $response = $this->get('bookmarklet/add?u=https://example.com/test&t=Example%20Title');

        $response->assertOk()->assertSee('A Link with that URL already exists.');
    }

    public function testLoginRedirectForBookmarklet(): void
    {
        $response = $this->get('bookmarklet/add?u=https://example.com&t=Example%20Title');

        $response->assertRedirect('bookmarklet/login')
            ->assertSessionHas('bookmarklet.new_url', 'https://example.com')
            ->assertSessionHas('bookmarklet.new_title', 'Example Title');
    }

    public function testValidBookmarkletWithTagsAndLists(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('bookmarklet/add?u=https://example.com&t=Example%20Title&tags=some%20%26%20tag&lists=a%20new%20list');

        $response->assertOk()
            ->assertSee('https://example.com')
            ->assertSee('Example Title')
            ->assertSee('some & tag')
            ->assertSee('a new list')
        ;
    }
}
