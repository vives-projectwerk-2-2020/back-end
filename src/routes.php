<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\User;
use App\Models\TodoItem;

use Slim\App;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode('hello world'));
        return $response;
    });
    $app->get('/Home', '\HomeController:home');

};
