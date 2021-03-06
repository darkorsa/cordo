<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require 'vendor/autoload.php';

$container = require_once __DIR__ . '/bootstrap/app.php';

return ConsoleRunner::createHelperSet($container->get('entity_manager'));
