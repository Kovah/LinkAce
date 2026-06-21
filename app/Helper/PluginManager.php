<?php

namespace App\Helper;

use App\Exceptions\PluginException;
use App\Plugins\NewLinkToWaybackMachine;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Reflector;
use ReflectionClass;
use ReflectionException;

class PluginManager
{
    /**
     * @throws PluginException
     */
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

    /**
     * @throws PluginException
     */
    private function getParameterTypesFor(object|string $pluginClass): array
    {
        try {
            $reflectionClass = new ReflectionClass($pluginClass);
        } catch (ReflectionException) {
            throw PluginException::pluginClassNotFound($pluginClass);
        }
        try {
            $reflectionMethod = $reflectionClass->getMethod('handle');
        } catch (ReflectionException) {
            throw PluginException::noHandleFunction($pluginClass);
        }
        $parameters = $reflectionMethod->getParameters();
        if (count($parameters) > 1) {
            throw PluginException::tooManyParameters($pluginClass);
        }
        if (count($parameters) < 1) {
            throw PluginException::noParameters($pluginClass);
        }
        return Reflector::getParameterClassNames($parameters[0]);
    }
}
