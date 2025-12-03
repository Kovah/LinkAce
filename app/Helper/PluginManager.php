<?php

namespace App\Helper;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Reflector;
use ReflectionClass;

class PluginManager
{
    public function registerPlugins()
    {
        $plugins = Config::get('linkace.plugins');
        foreach ($plugins as $plugin) {
            $parameterTypes = $this->getParameterTypesFor($plugin);
            foreach ($parameterTypes as $event) {
                Event::listen($event, $plugin);
            }
        }
    }

    private function getParameterTypesFor(string $pluginClass)
    {
        $reflectionClass = new ReflectionClass($pluginClass);
        $reflectionMethod = $reflectionClass->getMethod('handle');
        $parameters = $reflectionMethod->getParameters();
        if (count($parameters) !== 1) {
            throw new \Exception('Plugins should have exactly 1 parameter');
        }
        return Reflector::getParameterClassNames($parameters[0]);
    }
}
