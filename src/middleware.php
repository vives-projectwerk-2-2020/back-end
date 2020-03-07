<?php

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\App;

$dotenv = DotEnv\Dotenv::createImmutable(_DIR_);
$dotenv->load;
$dotenv->required(['PHP_URL']);

return function (App $app) {
    $app->add(function (Request $request, RequestHandler $handler) {
        $response = $handler->handle($request);
        return $response->withHeader('Content-Type', 'application/json')
                      ->withHeader('Access-Control-Allow-Origin', getenv('PHP_URL'));
    });
};
