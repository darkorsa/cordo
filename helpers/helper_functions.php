<?php

function env(string $index): string
{
    if (!array_key_exists($index, $_ENV)) {
        throw new OutOfBoundsException("{$index} is not a valid key for .env variables.");
    }
    
    return $_ENV[$index];
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
