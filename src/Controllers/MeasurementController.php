<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Measurement;

class MeasurementController extends AppController
{
    private $database;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->database = $container->get('influxDB');
    }

    public function getAllMeasurements(Request $request, Response $response, $args)
    {
        $measurements = Measurement::all();
        $response->getBody()->write(json_encode($measurements));
        return $response;
    }

    public function getMeasurements(Request $request, Response $response, $args)
    {
        $params = $request->getQueryParams();
        //peu input params in variables
        $period = $params["period"];
        $properties = $params["properties"];
        $id = $args["id"];

        $measurements = Measurement::find($id, $period, $properties);
        $response->getBody()->write(json_encode($measurements));
        return $response;
    }
}
