<?php

declare(strict_types=1);

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\App;

// $dotenv = Dotenv\Dotenv::createImmutable('../');
// $dotenv->load;
// $dotenv->required(['PHP_URL']);

return function (App $app) {
    $app->add(function (Request $request, RequestHandler $handler) {
        $response = $handler->handle($request);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000');
    });
};
