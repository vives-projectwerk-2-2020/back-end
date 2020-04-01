<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
        $database = $this->database;
        $result = $database->query('select * from sensors GROUP BY sensor_id');
        $response->getBody()->write(json_encode($result->getPoints()));
        return $response;
    }

    public function getMeasurements(Request $request, Response $response, $args)
    {
        $database = $this->database;
        $params = $request->getQueryParams();
        //peu input params in variables
        $period = $params["period"];
        $properties = $params["properties"];
        $id = $args["id"];
        //put the time parameter in easyer to process way
        $period_range =  substr($period, -1);
        $period_time = (int)substr($period, 0, -1);
        if ($period_range == "y") {
            $period_time *= 365;
            $period_range = "d";
        }

        //untested
        $new_date = $period_time . $period_range;
        //echo "select pm10,pm25,temperature,humidity FROM sensors WHERE sensor_id = $id AND time > now() - $new_date";
        $result = $database->query("select $properties FROM sensors WHERE sensor_id =~ /$id/ AND time > now() - $new_date");
        //remove time from response
        $decoded = $result->getPoints();
        for ($i = 0; $i < count($decoded); $i++) {
            unset($decoded[$i]['time']);
        }
        $response->getBody()->write(json_encode($decoded));
        return $response;
    }
}
