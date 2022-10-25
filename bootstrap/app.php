<?php

use Dotenv\Dotenv;
use Cordo\Core\Application\App;

error_reporting(E_ALL ^ E_DEPRECATED);

$dotenv = Dotenv::createImmutable(root_path());
$dotenv->load();

# craete application and init application
$app = App::create(root_path());
$app->init();

return $app;
