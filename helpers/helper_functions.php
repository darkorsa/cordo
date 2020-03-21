<?php

function env(string $index): string
{
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
