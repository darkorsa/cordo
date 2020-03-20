<?php

declare(strict_types=1);

namespace Cordo\Core\UI;

use Psr\Container\ContainerInterface;

class Locale
{
    public static function get(ContainerInterface $container, bool $isRunningInConsole): string
    {
        if ($isRunningInConsole) {
            return self::getConsoleLocale($container);
        }

        return self::getHttpLocale($container);
    }

    private static function getHttpLocale(ContainerInterface $container): string
    {
        $request = $container->get('request');

        return self::getLocaleForLang($request->getQueryParams()['lang'] ?? null, $container);
    }

    private static function getConsoleLocale(ContainerInterface $container): string
    {
        parse_str(implode('&', array_slice($_SERVER['argv'], 1)), $args);

        return self::getLocaleForLang($args['--lang'] ?? null, $container);
    }

    private static function getLocaleForLang(?string $lang, ContainerInterface $container): string
    {
        $config = $container->get('config');

        if (!in_array($lang, $config->get('trans.accepted_langs'))) {
            return $config->get('trans.fallback_locale');
        }

        return $config->get('trans.locales')[$lang];
    }
}
