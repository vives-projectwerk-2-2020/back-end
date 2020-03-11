<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\User;
use App\Models\TodoItem;

use \Curl\Curl;

use Slim\App;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {

        //http://localhost:8086/query?db=particula-influxdb_data&q=select%20*%20from%20sensors
        $response->getBody()->write(json_encode('hello world'));
        return $response;
    });
    $app->get('/foo', function (Request $request, Response $response) {
        //http://172.16.48.1:8086/query?db=particulaInfluxDB&q=select
        $client = new InfluxDB\Client(getenv('INFLUX_IP'), getenv('INFLUX_PORT'));
        $database = $client->selectDB('particulaInfluxDB');
        $result = $database->query('select * from sensors');
        $response->getBody()->write(json_encode($result->getPoints()));
        return $response;
    });
    //$app->get('/measurements')
};
