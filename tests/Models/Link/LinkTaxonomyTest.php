<?php

namespace Tests\Models\Link;

use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkTaxonomyTest extends TestCase
{
    use RefreshDatabase;

    public function testListTaxonomyOutput(): void
    {
        $link = Link::factory()->create();
        LinkList::factory()->count(5)->create();

        $link->lists()->sync([2, 3, 4]);

        $data = $link->oldTaxonomyOutput('lists');

        $parsedData = json_decode($data);

        $this->assertCount(3, $parsedData);
    }

    public function testTagsTaxonomyOutput(): void
    {
        $link = Link::factory()->create();
        Tag::factory()->count(5)->create();

        $link->tags()->sync([2, 3, 4]);

        $data = $link->oldTaxonomyOutput('tags');

        $parsedData = json_decode($data);

        $this->assertCount(3, $parsedData);
    }

    public function testTaxonomyOutputWithOldData(): void
    {
        $this->actingAs(User::factory()->create());

        Tag::factory()->count(5)->sequence(fn($sequence) => ['name' => 'Tag ' . $sequence->index])->create();
        Link::factory()->create(['url' => 'https://existing.com']);

        $link = Link::factory()->create(['url' => 'https://example.com']);
        $link->tags()->sync([2, 3]);

        $this->patch('links/2', [
            'url' => 'https://existing.com',
            'tags' => '2,3,test tag',
        ])->assertSessionHasErrors(['url']);

        $data = $link->oldTaxonomyOutput('tags');

        $parsedData = json_decode($data);

        $this->assertCount(3, $parsedData);
        $this->assertEquals('Tag 1', $parsedData[0]->name);
        $this->assertEquals('Tag 2', $parsedData[1]->name);
        $this->assertEquals('test tag', $parsedData[2]->name);
    }
}
