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

    $app->post('/addsensor', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $sensor = Sensor::create($data);
        $sensor->save();
        return $response;
    });

    $app->get('/sensors', function (Request $request, Response $response) {
        $sensors = Sensor::all();
        $jsontext = "[";
        foreach ($sensors as $sensor) {
            $location = array("latitude"=>$sensor->latitude,"longitude"=>$sensor->longitude,"city"=>$sensor->city, "address"=>$sensor->address);
            $sensor_json = json_encode(array("id"=>$sensor->id, "name"=>$sensor->name, "location"=>$location, "description"=>$sensor->description));

            $jsontext .= $sensor_json . ",";
        }
        $jsontext = substr_replace($jsontext, '', -1);
        $jsontext .= "]";

        $response->getBody()->write($jsontext);
        return $response;
    });

    $app->post('/adduser', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $sensor = User::create($data);
        $sensor->save();
        return $response;
    });

    $app->get('/user/{email}', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello world!");
        return $response;
    });
};
