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
        $host = "172.16.48.1";
        $port = 8086;
        $client = new InfluxDB\Client($host, $port);
        $database = $client->selectDB('particulaInfluxDB');
        $result = $database->query('select * from sensors');
        $response->getBody()->write(json_encode($result->getPoints()));
        return $response;
    });
    //$app->get('/me')
};
