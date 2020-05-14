<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Set up settings
$settings = require __DIR__ . '/../src/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($containerBuilder);

//overwrite notfoundhanler to return 404
$containerBuilder['notFoundHandler'] = function ($containerBuilder) {
    return function ($request, $response) use ($containerBuilder) {
        $error = json_encode("route not found");
        return $response->withJson($error, 404);
    };
};

// Build PHP-DI Container instance
$container = $containerBuilder->build();

//overwrite notfoundhanler to return 404
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $error = json_encode("route not found");
        return $response->withJson($error, 404);
    };
};

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

// Register middleware
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Register middleware
$middleware = require __DIR__ . '/../src/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

$app->getContainer()->get('Illuminate\Database\Capsule\Manager');
$app->getContainer()->get('influxDB');

// Run app
$app->run();
