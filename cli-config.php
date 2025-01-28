<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require 'vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

return ConsoleRunner::createHelperSet($app->entity_manager);
