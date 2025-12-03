<?php

namespace App\Exceptions;

class PluginException extends \Exception
{
    public static function tooManyParameters(object|string $class)
    {
        $className = self::resolveClassName($class);
        return new static("{$className}->handle() should contain exactly 1 parameter");
    }

    public static function noParameters(object|string $class)
    {
        $className = self::resolveClassName($class);
        return new static("{$className}->handle() does not specify any events to listen for");
    }

    public static function noHandleFunction(object|string $class)
    {
        $className = self::resolveClassName($class);
        return new static("required function {$className}->handle() does not exist");
    }

    public static function pluginClassNotFound(object|string $class)
    {
        $className = self::resolveClassName($class);
        return new static("plugin {$className} not found");
    }

    private static function resolveClassName(object|string $class)
    {
        return (is_object($class)) ? $class->getName() : $class;
    }


}
