<?php

namespace App\Helper;

use App\Events\LinkCreated;
use App\Plugins\NewLinkToWaybackMachine;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class PluginManager
{
    public function registerPlugins()
    {
        $plugins = Config::get('linkace.plugins');
        foreach ($plugins as $plugin) {
            foreach ($plugin::$events as $event) {
                Event::listen($event, $plugin);
                Log::debug("$plugin listening for $event");
            }
        }
    }
}
