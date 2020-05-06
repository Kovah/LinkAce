<?php

namespace Tests\Unit\Helper;

use App\Helper\UpdateHelper;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Class UpdateCheckTest
 *
 * @package Tests\Unit\Helper
 */
class UpdateCheckTest extends TestCase
{
    /**
     * Test the checkForUpdates() helper function with a new update available.
     * Must return the given version string.
     *
     * @return void
     */
    public function testSuccessfulCheck(): void
    {
        Http::fake([
            'github.com/*' => Http::response(
                [['tag_name' => 'v100.0.0']],
                200
            ),
        ]);

        $result = UpdateHelper::checkForUpdates();

        $this->assertEquals('v100.0.0', $result);
    }

    /**
     * Test the checkForUpdates() helper function with no update available.
     * Must return true.
     *
     * @return void
     */
    public function testSuccessfulCheckWithoutVersion(): void
    {
        Http::fake([
            'github.com/*' => Http::response(
                [['tag_name' => 'v0.0.0']],
                200
            ),
        ]);

        $result = UpdateHelper::checkForUpdates();

        $this->assertTrue($result);
    }

    /**
     * Test the checkForUpdates() helper function, but trigger a network / http error.
     * Must return false.
     *
     * @return void
     */
    public function testValidWaybackLink(): void
    {
        Http::fake([
            'github.com/*' => Http::response([], 404),
        ]);

        $result = UpdateHelper::checkForUpdates();

        $this->assertFalse($result);
    }
}
