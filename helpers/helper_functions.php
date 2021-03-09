<?php

use Cordo\Core\SharedKernel\Enum\Env;

function env(string $key, $default = null)
{
    $value = $_ENV[$key] ?? false;

    if ($value === false) {
        return $default;
    }

    switch (strtolower($value)) {
        case 'true':
            return true;
        case 'false':
            return false;
        case 'null':
            return null;
    }

    return $value;
}

function isDebug(): bool
{
    return env('APP_DEBUG');
}

function isProductionEnv(): bool
{
    return env('APP_ENV') == Env::PRODUCTION();
}

function isLocalEnv(): bool
{
    return env('APP_ENV') == Env::DEV();
}

function root_path(string $path = null): string
{
    return realpath(__DIR__ . '/..') . '/' . $path;
}

function app_path(string $path = null): string
{
    return realpath(__DIR__ . '/../app') . '/' . $path;
}

function config_path(string $path = null): string
{
    return realpath(__DIR__ . '/../config') . '/' . $path;
}

function storage_path(string $path = null): string
{
    return realpath(__DIR__ . '/../storage') . '/' . $path;
}

function resources_path(string $path = null): string
{
    return realpath(__DIR__ . '/../resources') . '/' . $path;
}

function vendor_path(string $path = null): string
{
    return realpath(__DIR__ . '/../vendor') . '/' . $path;
}
