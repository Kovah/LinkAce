<?php

namespace Tests\Unit\Helper;

use App\Helper\WaybackMachine;
use Tests\TestCase;

/**
 * Class WaybackMachineTest
 *
 * @package Tests\Unit\Helper
 */
class WaybackMachineTest extends TestCase
{
    /**
     * Test the saveToArchive() helper funtion with a valid URL.
     * Should return true.
     *
     * @return void
     */
    public function testValidWaybackAdding(): void
    {
        $url = 'https://google.com';

        $result = WaybackMachine::saveToArchive($url);

        $this->assertTrue($result);
    }

    /**
     * Test the saveToArchive() helper funtion with an invalid URL.
     * Will return false.
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
