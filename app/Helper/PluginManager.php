<?php

namespace App\Helper;

use App\Plugins\NewLinkToWaybackMachine;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades/Event;
use Illuminate\Support\Reflector;
use ReflectionClass;

class PluginManager
{
    public function registerPlugins(): void
    {
        $plugins = Config::get('linkace.plugins', [NewLinkToWaybackMachine::class]);
        foreach ($plugins as $plugin) {
            $parameterTypes = $this->getParameterTypesFor($plugin);
            foreach ($parameterTypes as $event) {
                Event::listen($event, $plugin);
            }
        }
    }

    private function getParameterTypesFor(string $pluginClass): array
    {
        $reflectionClass = new ReflectionClass($pluginClass);
        $reflectionMethod = $reflectionClass->getMethod('handle');
        $parameters = $reflectionMethod->getParameters();
        if (count($parameters) !== 1) {
            throw new \Exception("Plugin {$pluginClass} should have exactly 1 parameter in its handle method");
        }
        return Reflector::getParameterClassNames($parameters[0]);
    }
}
