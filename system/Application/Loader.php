<?php

namespace System\Application;

use System\UI\Http\Router;
use League\Event\EmitterInterface;
use Psr\Container\ContainerInterface;

class Loader
{
    public static function loadRoutes(Router $router, ContainerInterface $container): void
    {
        foreach (static::$register as $module) {
            $routesPath = static::routesPath($module);

            if (file_exists($routesPath)) {
                include_once $routesPath;
            }
        }
    }

    public static function loadDefinitions(): array
    {
        $definitions = include_once root_path() . 'bootstrap/definitions.php';

        foreach (static::$register as $module) {
            $definitionsPath = static::definitionsPath($module);

            if (file_exists($definitionsPath)) {
                $definitions = array_merge($definitions, include_once $definitionsPath);
            }
        }

        return $definitions;
    }

    public static function loadHandlersMap(): array
    {
        $handlersMap = [];
        foreach (static::$register as $module) {
            $handlersMapPath = static::handlersMapPath($module);

            if (file_exists($handlersMapPath)) {
                $handlersMap = array_merge($handlersMap, include_once $handlersMapPath);
            }
        }

        return $handlersMap;
    }

    public static function loadListeners(EmitterInterface $emitter, ContainerInterface $container): void
    {
        foreach (static::$register as $module) {
            $eventsPath = static::eventsPath($module);

            if (file_exists($eventsPath)) {
                include_once $eventsPath;
            }
        }
    }

    public static function loadEntities(): array
    {
        $paths = [];
        foreach (static::$register as $module) {
            $entitiesPath = static::entitiesPath($module);

            if (file_exists($entitiesPath)) {
                $paths[] = $entitiesPath;
            }
        }

        return $paths;
    }

    protected static function routesPath(string $module): string
    {
        return app_path().$module.'/UI/Http/routes.php';
    }

    protected static function definitionsPath(string $module): string
    {
        return app_path().$module.'/Application/definitions.php';
    }

    protected static function handlersMapPath(string $module): string
    {
        return app_path().$module.'/Application/handlers.php';
    }

    protected static function eventsPath(string $module): string
    {
        return app_path().$module.'/Application/events.php';
    }

    protected static function entitiesPath(string $module): string
    {
        return app_path().$module.'/Infrastructure/Doctrine';
    }
}
