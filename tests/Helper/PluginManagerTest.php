<?php

namespace Tests\Helper;

use App\Events\LinkCreated;
use App\Events\LinkUpdated;
use App\Plugins\NewLinkToWaybackMachine;
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

    public function test_plugin_manager_registers_single_listener()
    {
        Config::set('linkace.plugins', [NewLinkToWaybackMachine::class]);
        PluginManager::registerPlugins();
        Event::assertListening(LinkCreated::class, NewLinkToWaybackMachine::class);
    }

    public function test_plugin_manager_registers_all_events_for_union_types()
    {
        Config::set('linkace.plugins', [SamplePlugin::class]);
        PluginManager::registerPlugins();
        Event::assertListening(LinkCreated::class, SamplePlugin::class);
        Event::assertListening(LinkUpdated::class, SamplePlugin::class);
    }

    public function test_plugin_manager_handles_multiple_plugins()
    {
        Config::set('linkace.plugins', [SamplePlugin::class, NewLinkToWaybackMachine::class]);
        PluginManager::registerPlugins();
        Event::assertListening(LinkCreated::class, SamplePlugin::class);
        Event::assertListening(LinkCreated::class, NewLinkToWaybackMachine::class);
        Event::assertListening(LinkUpdated::class, SamplePlugin::class);
    }

}
