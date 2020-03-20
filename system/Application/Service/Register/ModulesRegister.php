<?php

declare(strict_types=1);

namespace Cordo\Core\Application\Service\Register;

use DI\Container;
use Noodlehaus\Config;
use League\Plates\Engine;
use Cordo\Core\UI\Http\Router;
use Laminas\Permissions\Acl\Acl;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use League\Event\EmitterInterface;
use Cordo\Core\SharedKernel\Enum\Scope;
use Psr\Container\ContainerInterface;
use Cordo\Core\Application\Config\Parser;
use Symfony\Component\Console\Application;
use Symfony\Component\Translation\Translator;

class ModulesRegister
{
    protected static $register = [];

    private static $systemModules = [
        'Welcome\Message',
    ];

    public static function initModules(Container $container, bool $isRunningInConsole): void
    {
        $initModule = function (string $context, string $module, Scope $scope) use ($container, $isRunningInConsole): void {
            $className = self::getModuleInitClassName($context, $module, $scope);

            if (!class_exists($className)) {
                return;
            }

            $className::init($container, $isRunningInConsole);
        };

        self::call($initModule);
    }

    public static function registerRoutes(Router $router, ContainerInterface $container): void
    {
        $registerRoutes = function (string $context, string $module, Scope $scope) use ($router, $container): void {
            $className = self::routesClassname($context, $module, $scope);

            if (!class_exists($className)) {
                return;
            }

            $routesRegister = new $className($router, $container, strtolower($module));
            $routesRegister->register();
        };

        self::call($registerRoutes);
    }

    public static function registerCommands(Application $application, ContainerInterface $container): void
    {
        $registerCommands = function (
            string $context,
            string $module,
            Scope $scope
        ) use (
            $application,
            $container
        ): void {
            $commandsPath = self::commandsPath($context, $module, $scope);

            if (!file_exists($commandsPath)) {
                return;
            }

            $commands = include_once $commandsPath;

            array_map(static function ($command) use ($application, $container) {
                $application->add($container->get($command));
            }, $commands);
        };

        self::call($registerCommands);
    }

    public static function registerDefinitions(): array
    {
        $getDefinitions = function (string $context, string $module, Scope $scope): array {
            $definitionsPath = self::definitionsPath($context, $module, $scope);

            if (file_exists($definitionsPath)) {
                return include_once $definitionsPath;
            }

            return [];
        };

        $definitions = include_once root_path() . 'bootstrap/definitions.php';

        // system definitions
        foreach (self::$systemModules as $moduleInContext) {
            [$context, $module] = explode('\\', $moduleInContext);
            $definitions = array_merge($definitions, $getDefinitions($context, $module, SCOPE::SYSTEM()));
        }

        // app definitions
        foreach (static::$register as $moduleInContext) {
            [$context, $module] = explode('\\', $moduleInContext);
            $definitions = array_merge($definitions, $getDefinitions($context, $module, SCOPE::APP()));
        }

        return $definitions;
    }

    public static function registerConfigs(Config $config): void
    {
        $registerConfigs = function (string $context, string $module, Scope $scope) use ($config): void {
            $configsPath = self::configsPath($context, $module, $scope);

            if (file_exists($configsPath)) {
                $moduleConfig = new Config($configsPath, new Parser());
                $config->merge($moduleConfig);
            }
        };

        self::call($registerConfigs);
    }

    public static function registerHandlersMap(): array
    {
        $getHandlers = function (string $context, string $module, Scope $scope): array {
            $handlersMapPath = self::handlersMapPath($context, $module, $scope);

            if (file_exists($handlersMapPath)) {
                return include_once $handlersMapPath;
            }

            return [];
        };

        $handlersMap = [];

        // system handlers
        foreach (self::$systemModules as $moduleInContext) {
            [$context, $module] = explode('\\', $moduleInContext);
            $handlersMap = array_merge($handlersMap, $getHandlers($context, $module, SCOPE::SYSTEM()));
        }

        // app handlers
        foreach (static::$register as $moduleInContext) {
            [$context, $module] = explode('\\', $moduleInContext);
            $handlersMap = array_merge($handlersMap, $getHandlers($context, $module, SCOPE::APP()));
        }

        return $handlersMap;
    }

    public static function registerListeners(EmitterInterface $emitter, ContainerInterface $container): void
    {
        $registerListeners = function (string $context, string $module, Scope $scope) use ($emitter, $container): void {
            $className = self::listenerClassname($context, $module, $scope);

            if (!class_exists($className)) {
                return;
            }

            $eventsRegister = new $className($emitter, $container, strtolower($module));
            $eventsRegister->register();
        };

        self::call($registerListeners);
    }

    public static function registerEntities(): array
    {
        $paths = [];

        $registerEntities = function (string $context, string $module, Scope $scope) use (&$paths): void {
            $entitiesPath = self::entitiesPath($context, $module, $scope);

            if (file_exists($entitiesPath)) {
                $paths[] = $entitiesPath;
            }
        };

        self::call($registerEntities);

        return $paths;
    }

    public static function registerAclData(Acl $acl): void
    {
        $registerAcl = function (string $context, string $module, Scope $scope) use ($acl): void {
            $className = self::aclClassname($context, $module, $scope);

            if (!class_exists($className)) {
                return;
            }

            $aclRegister = new $className($acl, strtolower($module));
            $aclRegister->register();
        };

        self::call($registerAcl);
    }

    public static function registerViews(Engine $templates): void
    {
        $registerViews = function (string $context, string $module, Scope $scope) use ($templates): void {
            $viewsPath = self::viewsPath($context, $module, $scope);

            if (file_exists($viewsPath)) {
                $templates->addFolder(strtolower($module), $viewsPath);
            }
        };

        self::call($registerViews);
    }

    public static function registerTranslations(Translator $translator, ContainerInterface $container): void
    {
        $config = $container->get('config');

        $registerTranslations = function (
            string $context,
            string $module,
            Scope $scope
        ) use (
            $translator,
            $config
        ): void {
            $translationsPath = self::translationsPath($context, $module, $scope);

            if (file_exists($translationsPath)) {
                $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($translationsPath));

                foreach ($rii as $file) {
                    if ($file->isDir()) {
                        continue;
                    }

                    if (preg_match('/([a-z0-9]+)\.([a-z]{2})\.yaml/', $file->getPathname(), $matches)) {
                        $locale = $config->get('trans.locales')[$matches[2]] ?? null;
                        if (!$locale) {
                            continue;
                        }

                        $translator->addResource('yaml', $file->getPathname(), $locale, $matches[1]);
                    }
                }
            }
        };

        self::call($registerTranslations);
    }

    private static function call(callable $function)
    {
        // system
        foreach (self::$systemModules as $moduleInContext) {
            [$context, $module] = explode('\\', $moduleInContext);

            $function($context, $module, Scope::SYSTEM());
        }

        // app
        foreach (static::$register as $moduleInContext) {
            [$context, $module] = explode('\\', $moduleInContext);

            $function($context, $module, Scope::APP());
        }
    }

    private static function getModuleNamespace(string $context, string $module, Scope $scope)
    {
        return $scope == SCOPE::APP()
            ? "App\\{$context}\\{$module}"
            : "Cordo\Core\Module\\{$context}\\{$module}";
    }

    private static function getModulePath(string $context, string $module, Scope $scope)
    {
        return $scope == SCOPE::APP()
            ? app_path() . $context . '/' . $module
            : system_path() . 'Module/' . $context . '/' . $module;
    }

    private static function getModuleInitClassName(string $context, string $module, Scope $scope): string
    {
        return self::getModuleNamespace($context, $module, $scope) . "\\{$module}Init";
    }

    private static function routesClassname(string $context, string $module, Scope $scope): string
    {
        return self::getModuleNamespace($context, $module, $scope) . "\UI\Http\Route\\{$module}Routes";
    }

    private static function commandsPath(string $context, string $module, Scope $scope): string
    {
        return self::getModulePath($context, $module, $scope) . "/UI/Console/commands.php";
    }

    private static function definitionsPath(string $context, string $module, Scope $scope): string
    {
        return self::getModulePath($context, $module, $scope) . "/Application/definitions.php";
    }

    protected static function configsPath(string $context, string $module, Scope $scope): string
    {
        return self::getModulePath($context, $module, $scope) . "/Application/config";
    }

    private static function handlersMapPath(string $context, string $module, Scope $scope): string
    {
        return self::getModulePath($context, $module, $scope) . "/Application/handlers.php";
    }

    private static function listenerClassname(string $context, string $module, Scope $scope): string
    {
        return self::getModuleNamespace($context, $module, $scope) . "\Application\Event\Register\\{$module}Listeners";
    }

    private static function entitiesPath(string $context, string $module, Scope $scope): string
    {
        return self::getModulePath($context, $module, $scope)
            . "/Infrastructure/Persistance/Doctrine/ORM/Metadata";
    }

    private static function aclClassname(string $context, string $module, Scope $scope): string
    {
        return self::getModuleNamespace($context, $module, $scope) . "\Application\Acl\\{$module}Acl";
    }

    private static function viewsPath(string $context, string $module, Scope $scope): string
    {
        return self::getModulePath($context, $module, $scope) . "/UI/views";
    }

    private static function translationsPath(string $context, string $module, Scope $scope): string
    {
        return self::getModulePath($context, $module, $scope) . "/UI/trans";
    }
}
