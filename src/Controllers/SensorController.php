<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Sensor;

class SensorController extends AppController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, $args)
    {
        $sensors = Sensor::all();
        $jsontext = "[";
        foreach ($sensors as $sensor) {
            $location = array("latitude"=>$sensor->latitude,"longitude"=>$sensor->longitude
                , "city"=>$sensor->city, "address"=>$sensor->address);
            $sensor_json = json_encode(array("id"=>$sensor->id, "name"=>$sensor->name
                , "location"=>$location, "description"=>$sensor->description));

            $jsontext .= $sensor_json . ",";
        }

        if ($sensors->count() > 0) {
            $jsontext = substr_replace($jsontext, '', -1);
        }
        $jsontext .= "]";

        $response->getBody()->write($jsontext);
        return $response;
    }

    public function create(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $sensor = Sensor::create($data);
        $sensor->save();
        return $response;
    }
}
