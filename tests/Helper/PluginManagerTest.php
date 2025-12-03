<?php

namespace Tests\Helper;

use App\Events\LinkUpdated;
use Facades\App\Helper\PluginManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Tests\Plugins\SamplePlugin;
use Tests\TestCase;

class PluginManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function test_plugin_manager_doesnt_bomb_with_no_plugins()
    {
        Config::set('linkace.plugins', []);
        PluginManager::registerPlugins();
        $this->assertTrue(true, 'no exception thrown');
    }

    public function test_plugin_manager_registers_event_listeners()
    {
        Config::set('linkace.plugins', [SamplePlugin::class]);
        PluginManager::registerPlugins();
        Event::assertListening(LinkUpdated::class, SamplePlugin::class);
    }
}
