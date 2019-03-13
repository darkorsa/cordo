<?php

define("ROOT_DIR", __DIR__.'/../');

function root_path()
{
    return ROOT_DIR;
}

function app_path()
{
    return ROOT_DIR . 'app/';
}

function config_path()
{
    return ROOT_DIR . 'config/';
}

function system_path()
{
    return ROOT_DIR . 'system/';
}

function resources_path()
{
    return ROOT_DIR . 'resources/';
}

function storage_path()
{
    return ROOT_DIR . 'storage/';
}

function vendor_path()
{
    return ROOT_DIR . 'vendor/';
}
