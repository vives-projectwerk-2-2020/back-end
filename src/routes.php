<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Sensor;
use App\Models\Location;

use Slim\App;

return function (App $app) {
    $app->get('/hello', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello world!");
        return $response;
    });
    $app->post('/sensor', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $sensor = Sensor::create($data);
        $sensor->save();
        return $response;
    });
    $app->get('/sensor', function (Request $request, Response $response) {
        $sensor = Sensor::all();
        $jsonSensor = json_encode($sensor);
        $response->getBody()->write($jsonSensor);
        return $response;
    });
};
