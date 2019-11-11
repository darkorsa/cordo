<?php

declare(strict_types=1);

namespace System\Application\Service\Register;

use Noodlehaus\Config;
use System\UI\Http\Router;
use Zend\Permissions\Acl\Acl;
use League\Event\EmitterInterface;
use Psr\Container\ContainerInterface;
use System\Application\Config\Parser;
use Symfony\Component\Console\Application;

class ModulesRegister
{
    protected static $register = [];

    public static function getModules(): array
    {
        return static::$register;
    }

    public static function registerRoutes(Router $router, ContainerInterface $container): void
    {
        foreach (static::$register as $module) {
            $className = "App\\{$module}\UI\Http\Route\\{$module}Routes";

            if (!class_exists($className)) {
                continue;
            }

            $routesRegister = new $className($router, $container, strtolower($module));
            $routesRegister->register();
        }
    }

    public static function registerCommands(Application $application, ContainerInterface $container): void
    {
        foreach (static::$register as $module) {
            $commandsPath = static::commandsPath($module);

            if (!file_exists($commandsPath)) {
                continue;
            }

            $commands = include_once $commandsPath;

            array_map(static function ($command) use ($application, $container) {
                $application->add($container->get($command));
            }, $commands);
        }
    }

    public static function registerDefinitions(): array
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

    public static function registerConfigs(Config $config): void
    {
        foreach (static::$register as $module) {
            $configsPath = static::configsPath($module);

            if (file_exists($configsPath)) {
                $moduleConfig = new Config($configsPath, new Parser());
                $config->merge($moduleConfig);
            }
        }
    }

    public static function registerHandlersMap(): array
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

    public static function registerListeners(EmitterInterface $emitter, ContainerInterface $container): void
    {
        foreach (static::$register as $module) {
            $className = "App\\{$module}\Application\Event\Register\\{$module}Listeners";

            if (!class_exists($className)) {
                continue;
            }

            $eventsRegister = new $className($emitter, $container, strtolower($module));
            $eventsRegister->register();
        }
    }

    public static function registerEntities(): array
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

    public static function registerAclData(Acl $acl): void
    {
        foreach (static::$register as $module) {
            $className = "App\\{$module}\Application\Acl\\{$module}Acl";

            if (!class_exists($className)) {
                continue;
            }

            $aclRegister = new $className($acl, strtolower($module));
            $aclRegister->register();
        }
    }

    protected static function commandsPath(string $module): string
    {
        return app_path() . $module . '/UI/Console/commands.php';
    }

    protected static function definitionsPath(string $module): string
    {
        return app_path() . $module . '/Application/definitions.php';
    }

    protected static function configsPath(string $module): string
    {
        return app_path() . $module . '/Application/config';
    }

    protected static function handlersMapPath(string $module): string
    {
        return app_path() . $module . '/Application/handlers.php';
    }

    protected static function entitiesPath(string $module): string
    {
        return app_path() . $module . '/Infrastructure/Persistance/Doctrine/ORM/Metadata';
    }
}
