<?php

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

function root_path(): string
{
    return __DIR__ . '/../';
}

function app_path(): string
{
    return __DIR__ . '/../app/';
}

function config_path(): string
{
    return __DIR__ . '/../config/';
}

function resources_path(): string
{
    return __DIR__ . '/../resources/';
}

function storage_path(): string
{
    return __DIR__ . '/../storage/';
}

function vendor_path(): string
{
    return __DIR__ . '/../vendor/';
}
