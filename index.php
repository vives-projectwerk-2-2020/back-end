<?php

use Slim\Factory\AppFactory;
use DI\ContainerInterface;

require __DIR__.'/vendor/autoload.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

$middleware = require __DIR__ . '/src/middleware.php';
$middleware($app);

$routes = require __DIR__ . '/src/routes.php';
$routes($app);

$app->run();
