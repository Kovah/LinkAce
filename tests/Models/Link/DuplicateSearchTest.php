<?php

namespace Tests\Models\Link;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DuplicateSearchTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @var User */
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testWithoutDuplicates(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isEmpty());
    }

    public function testScheme(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'http://example.com',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function testTrailingSlashes(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://example.com/',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function testUrlFragments(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://example.com#anchor',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function testQueryParameters(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://example.com',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://example.com?s=testing',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function testUrlWithPort(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://localhost:8080',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://localhost:8080/',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function testUrlWithSimpleAuth(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://:password@ftp.cia.gov',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://:password@ftp.cia.gov/',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function testUrlWithFullAuth(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'https://:snowden@files.nsa.gov',
        ]);

        /** @var Link $duplicateLink */
        $duplicateLink = Link::factory()->create([
            'url' => 'https://admin:snowden@files.nsa.gov/',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isNotEmpty());
        $this->assertTrue($check->contains('id', $duplicateLink->id));
    }

    public function testBrokenUrl(): void
    {
        /** @var Link $link */
        $link = Link::factory()->create([
            'url' => 'example.com',
        ]);

        $check = $link->searchDuplicateUrls();

        $this->assertTrue($check->isEmpty());
    }
}
