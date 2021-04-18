<?php

namespace Tests\Helper;

use App\Helper\UpdateHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateCheckTest extends TestCase
{
    /*
     * Test the checkForUpdates() helper function with a new update available.
     * Must return the given version string.
     */
    public function testSuccessfulCheck(): void
    {
        Http::fake([
            '*' => Http::response('v100.0.0'),
        ]);

        $result = UpdateHelper::checkForUpdates();

        $this->assertEquals('v100.0.0', $result);
    }

    /*
     * Test the checkForUpdates() helper function with no update available.
     * Must return true.
     */
    public function testSuccessfulCheckWithoutVersion(): void
    {
        Http::fake([
            '*' => Http::response('v0.0.0'),
        ]);

        $result = UpdateHelper::checkForUpdates();

        $this->assertTrue($result);
    }

    /*
     * Test the checkForUpdates() helper function, but trigger a network / http error.
     * Must return false.
     */
    public function testUpdateCheckWithNetworkError(): void
    {
        Http::fake([
            '*' => Http::response('', 404),
        ]);

        $result = UpdateHelper::checkForUpdates();

        $this->assertFalse($result);
    }

    /*
     * Test if the UpdateHelper correctly returns a version from the package.json file.
     */
    public function testVersionFromPackage(): void
    {
        Storage::fake('root')->put('package.json', '{"version":"0.0.39"}');

        $version = UpdateHelper::currentVersion();

        $this->assertEquals('v0.0.39', $version);
    }

    /*
     * The UpdateHelper should return null if there is no version field.
     */
    public function testVersionFromPackageWithInvalidFile(): void
    {
        Storage::fake('root')->put('package.json', '{"foo":"bar"}');

        $version = UpdateHelper::currentVersion();

        $this->assertNull($version);
    }

    /*
     * The UpdateHelper should return null if no package.json file was found.
     */
    public function testVersionFromPackageWithMissingFile(): void
    {
        Storage::fake('root');

        $version = UpdateHelper::currentVersion();

        $this->assertNull($version);
    }
}
