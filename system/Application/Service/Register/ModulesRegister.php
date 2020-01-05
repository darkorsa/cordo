<?php

declare(strict_types=1);

namespace System\Application\Service\Register;

use Noodlehaus\Config;
use League\Plates\Engine;
use System\UI\Http\Router;
use Zend\Permissions\Acl\Acl;
use League\Event\EmitterInterface;
use System\SharedKernel\Enum\Scope;
use Psr\Container\ContainerInterface;
use System\Application\Config\Parser;
use Symfony\Component\Console\Application;

class ModulesRegister
{
    protected static $register = [];

    private static $systemModules = [
        'Auth',
        'Welcome',
    ];

    public static function initModules(ContainerInterface $container, bool $isConsole): void
    {
        $initModule = function (string $module, Scope $scope) use ($container, $isConsole): void {
            $className = self::getModuleInitClassName($module, $scope);

            if (!class_exists($className)) {
                return;
            }

            $className::init($container, $isConsole);
        };

        // init system modules
        foreach (self::$systemModules as $module) {
            $initModule($module, SCOPE::SYSTEM());
        }

        // init app modules
        foreach (static::$register as $module) {
            $initModule($module, SCOPE::APP());
        }
    }

    public static function registerRoutes(Router $router, ContainerInterface $container): void
    {
        $registerRoutes = function (string $module, Scope $scope) use ($router, $container): void {
            $className = self::routesClassname($module, $scope);

            if (!class_exists($className)) {
                return;
            }

            $routesRegister = new $className($router, $container, strtolower($module));
            $routesRegister->register();
        };

        // register system routes
        foreach (self::$systemModules as $module) {
            $registerRoutes($module, SCOPE::SYSTEM());
        }

        // register app routes
        foreach (static::$register as $module) {
            $registerRoutes($module, SCOPE::APP());
        }
    }

    public static function registerCommands(Application $application, ContainerInterface $container): void
    {
        $registerCommands = function (string $module, Scope $scope) use ($application, $container): void {
            $commandsPath = self::commandsPath($module, $scope);

            if (!file_exists($commandsPath)) {
                return;
            }

            $commands = include_once $commandsPath;

            array_map(static function ($command) use ($application, $container) {
                $application->add($container->get($command));
            }, $commands);
        };

        // register system commands
        foreach (self::$systemModules as $module) {
            $registerCommands($module, SCOPE::SYSTEM());
        }

        // register app commands
        foreach (static::$register as $module) {
            $registerCommands($module, SCOPE::APP());
        }
    }

    public static function registerDefinitions(): array
    {
        $getDefinitions = function (string $module, Scope $scope): array {
            $definitionsPath = self::definitionsPath($module, $scope);

            if (file_exists($definitionsPath)) {
                return include_once $definitionsPath;
            }

            return [];
        };

        $definitions = include_once root_path() . 'bootstrap/definitions.php';

        // system definitions
        foreach (self::$systemModules as $module) {
            $definitions = array_merge($definitions, $getDefinitions($module, SCOPE::SYSTEM()));
        }

        // app definitions
        foreach (static::$register as $module) {
            $definitions = array_merge($definitions, $getDefinitions($module, SCOPE::APP()));
        }

        return $definitions;
    }

    public static function registerConfigs(Config $config): void
    {
        $registerConfigs = function (string $module, Scope $scope) use ($config): void {
            $configsPath = self::configsPath($module, $scope);

            if (file_exists($configsPath)) {
                $moduleConfig = new Config($configsPath, new Parser());
                $config->merge($moduleConfig);
            }
        };

        // register system configs
        foreach (self::$systemModules as $module) {
            $registerConfigs($module, SCOPE::SYSTEM());
        }

        // register app configs
        foreach (static::$register as $module) {
            $registerConfigs($module, Scope::APP());
        }
    }

    public static function registerHandlersMap(): array
    {
        $getHandlers = function (string $module, Scope $scope): array {
            $handlersMapPath = self::handlersMapPath($module, $scope);

            if (file_exists($handlersMapPath)) {
                return include_once $handlersMapPath;
            }

            return [];
        };

        $handlersMap = [];

        // system handlers
        foreach (self::$systemModules as $module) {
            $handlersMap = array_merge($handlersMap, $getHandlers($module, SCOPE::SYSTEM()));
        }

        // app handlers
        foreach (static::$register as $module) {
            $handlersMap = array_merge($handlersMap, $getHandlers($module, SCOPE::APP()));
        }

        return $handlersMap;
    }

    public static function registerListeners(EmitterInterface $emitter, ContainerInterface $container): void
    {
        $registerListeners = function (string $module, Scope $scope) use ($emitter, $container): void {
            $className = self::listenerClassname($module, $scope);

            if (!class_exists($className)) {
                return;
            }

            $eventsRegister = new $className($emitter, $container, strtolower($module));
            $eventsRegister->register();
        };

        // system listeneres
        foreach (self::$systemModules as $module) {
            $registerListeners($module, Scope::SYSTEM());
        }

        // app listeneres
        foreach (static::$register as $module) {
            $registerListeners($module, Scope::APP());
        }
    }

    public static function registerEntities(): array
    {
        $paths = [];

        $registerEntities = function (string $module, Scope $scope) use (&$paths): void {
            $entitiesPath = self::entitiesPath($module, $scope);

            if (file_exists($entitiesPath)) {
                $paths[] = $entitiesPath;
            }
        };

        // system entities
        foreach (self::$systemModules as $module) {
            $registerEntities($module, Scope::SYSTEM());
        }

        // app entities
        foreach (static::$register as $module) {
            $registerEntities($module, Scope::APP());
        }

        return $paths;
    }

    public static function registerAclData(Acl $acl): void
    {
        $registerAcl = function (string $module, Scope $scope) use ($acl): void {
            $className = self::aclClassname($module, $scope);

            if (!class_exists($className)) {
                return;
            }

            $aclRegister = new $className($acl, strtolower($module));
            $aclRegister->register();
        };

        // system acl data
        foreach (self::$systemModules as $module) {
            $registerAcl($module, Scope::SYSTEM());
        }

        // app acl data
        foreach (static::$register as $module) {
            $registerAcl($module, Scope::APP());
        }
    }

    public static function registerViews(Engine $templates): void
    {
        $registerViews = function (string $module, Scope $scope) use ($templates): void {
            $viewsPath = self::viewsPath($module, $scope);

            if (file_exists($viewsPath)) {
                $templates->addFolder(strtolower($module), $viewsPath);
            }
        };

        // system acl data
        foreach (self::$systemModules as $module) {
            $registerViews($module, Scope::SYSTEM());
        }

        // app acl data
        foreach (static::$register as $module) {
            $registerViews($module, Scope::APP());
        }
    }

    private static function getModuleInitClassName(string $module, Scope $scope): string
    {
        return $scope == SCOPE::APP()
            ? "App\\{$module}\\{$module}Init"
            : "System\Module\\{$module}\\{$module}Init";
    }

    private static function routesClassname(string $module, Scope $scope): string
    {
        return $scope == SCOPE::APP()
            ? "App\\{$module}\UI\Http\Route\\{$module}Routes"
            : "System\Module\\{$module}\UI\Http\Route\\{$module}Routes";
    }

    private static function commandsPath(string $module, Scope $scope): string
    {
        return $scope == SCOPE::APP()
            ? app_path() . $module . '/UI/Console/commands.php'
            : system_path() . 'Module/' . $module . '/UI/Console/commands.php';
    }

    private static function definitionsPath(string $module, Scope $scope): string
    {
        return $scope == SCOPE::APP()
            ? app_path() . $module . '/Application/definitions.php'
            : system_path() . 'Module/' . $module . '/Application/definitions.php';
    }

    protected static function configsPath(string $module, Scope $scope): string
    {
        return $scope == SCOPE::APP()
            ? app_path() . $module . '/Application/config'
            : system_path() . 'Module/' . $module . '/Application/config';
    }

    private static function handlersMapPath(string $module, Scope $scope): string
    {
        return $scope == SCOPE::APP()
            ? app_path() . $module . '/Application/handlers.php'
            : system_path() . 'Module/' . $module . '/Application/handlers.php';
    }

    private static function listenerClassname(string $module, Scope $scope): string
    {
        return $scope == SCOPE::APP()
            ? "App\\{$module}\Application\Event\Register\\{$module}Listeners"
            : "System\Module\\{$module}\Application\Event\Register\\{$module}Listeners";
    }

    private static function entitiesPath(string $module, Scope $scope): string
    {
        return $scope == SCOPE::APP()
            ? app_path() . $module . '/Infrastructure/Persistance/Doctrine/ORM/Metadata'
            : system_path() . 'Module/' . $module . '/Infrastructure/Persistance/Doctrine/ORM/Metadata';
    }

    private static function aclClassname(string $module, Scope $scope): string
    {
        return $scope == SCOPE::APP()
            ? "App\\{$module}\Application\Acl\\{$module}Acl"
            : "System\Module\\{$module}\Application\Acl\\{$module}Acl";
    }

    private static function viewsPath(string $module, Scope $scope): string
    {
        return $scope == SCOPE::APP()
            ? app_path() . $module . '/UI/views'
            : system_path() . 'Module/' . $module . '/UI/views';
    }
}
