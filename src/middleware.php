<?php

require __DIR__ . './../vendor/autoload.php';

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\App;


return function (App $app) {
    $app->add(function (Request $request, RequestHandler $handler) {
        $response = $handler->handle($request);
        return $response->withHeader('Content-Type', 'application/json')
                      ->withHeader('Access-Control-Allow-Origin','0.0.0.0:3000');
    });
};
