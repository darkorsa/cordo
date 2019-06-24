<?php

define("ROOT_DIR", __DIR__.'/../');

function root_path(): string
{
    return ROOT_DIR;
}

function app_path(): string
{
    return ROOT_DIR . 'app/';
}

function config_path(): string
{
    return ROOT_DIR . 'config/';
}

function system_path(): string
{
    return ROOT_DIR . 'system/';
}

function resources_path(): string
{
    return ROOT_DIR . 'resources/';
}

function storage_path(): string
{
    return ROOT_DIR . 'storage/';
}

function vendor_path(): string
{
    return ROOT_DIR . 'vendor/';
}
