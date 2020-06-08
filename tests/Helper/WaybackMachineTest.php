<?php

namespace Tests\Helper;

use App\Helper\WaybackMachine;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WaybackMachineTest extends TestCase
{
    /**
     * Test the saveToArchive() helper funtion with a valid URL.
     * Must return true.
     *
     * @return void
     */
    public function testValidWaybackAdding(): void
    {
        Http::fake([
            'web.archive.org/*' => Http::response([], 200),
        ]);

        $url = 'https://google.com';

        $result = WaybackMachine::saveToArchive($url);

        $this->assertTrue($result);
    }

    /**
     * Test the saveToArchive() helper funtion with a valid URL, but a network error.
     * Must return false.
     *
     * @return void
     */
    public function testValidWaybackAddingWithNetworkError(): void
    {
        Http::fake([
            'web.archive.org/*' => Http::response([], 404),
        ]);

        $url = 'https://google.com';

        $result = WaybackMachine::saveToArchive($url);

        $this->assertFalse($result);
    }

    /**
     * Test the saveToArchive() helper funtion with an invalid URL.
     * Must return false.
     *
     * @return void
     */
    public function testInvalidWaybackAdding(): void
    {
        $url = 'not an URL';

        $result = WaybackMachine::saveToArchive($url);

        $this->assertFalse($result);
    }

    /**
     * Test the saveToArchive() helper funtion with a valid URL.
     * Should return true.
     *
     * @return void
     */
    public function testValidWaybackLink(): void
    {
        $url = 'https://google.com';

        $link = WaybackMachine::getArchiveLink($url);

        $this->assertEquals('https://web.archive.org/web/*/https://google.com', $link);
    }

    /**
     * Test the saveToArchive() helper funtion with an invalid URL.
     * Will return false.
     *
     * @return void
     */
    public function testInvalidWaybackLink(): void
    {
        $url = 'not an URL';

        $link = WaybackMachine::getArchiveLink($url);

        $this->assertNull($link);
    }
}
