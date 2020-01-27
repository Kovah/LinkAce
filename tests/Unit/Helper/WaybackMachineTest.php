<?php

namespace Tests\Unit\Helper;

use App\Helper\WaybackMachine;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

/**
 * Class WaybackMachineTest
 *
 * @package Tests\Unit\Helper
 */
class WaybackMachineTest extends TestCase
{
    /** @var WaybackMachine */
    private $waybackHelper;

    /**
     * During setup we create a Mock Handler to prevent tests from running real
     * queries to archive.org, instead the Mock Handler answers the corresponding
     * request.
     */
    protected function setUp(): void
    {
        $mock = new MockHandler([new Response(200)]);

        $handlerStack = HandlerStack::create($mock);
        $clientConfig = ['handler' => $handlerStack];

        $this->waybackHelper = new WaybackMachine($clientConfig);

        parent::setUp();
    }

    /**
     * Test the saveToArchive() helper funtion with a valid URL.
     * Should return true.
     *
     * @return void
     */
    public function testValidWaybackAdding(): void
    {
        $url = 'https://google.com';

        $result = $this->waybackHelper::saveToArchive($url);

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

        $result = $this->waybackHelper::saveToArchive($url);

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

        $link = $this->waybackHelper::getArchiveLink($url);

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

        $link = $this->waybackHelper::getArchiveLink($url);

        $this->assertNull($link);
    }
}
