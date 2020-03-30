<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\User;
use App\Models\TodoItem;

use \Curl\Curl;

use Slim\App;


return function (App $app) {
    $app->get('/', function (Request $request, Response $response)
    {

        $time = date('y-m-d\TH:i:s');
        $response->getBody()->write(json_encode($time));
        $test =  substr("qwerty",0,2);
        echo $test;


        return $response;
    });
    $app->get('/foo', function (Request $request, Response $response)
    {
        $client = new InfluxDB\Client(getenv('INFLUX_IP'), getenv('INFLUX_PORT'));
        $database = $client->selectDB('particulaInfluxDB');
        $result = $database->query('select * from sensors GROUP BY sensor_id');
        $response->getBody()->write(json_encode($result->getPoints()));
        return $response;
    });
    $app->get('/measurements/{id}', function($request, $response, $args)
    {
        //make new request for mysql
        $client = new InfluxDB\Client(getenv('INFLUX_IP'), getenv('INFLUX_PORT'));
        $database = $client->selectDB('particulaInfluxDB');
        $params = $request->getQueryParams();
        //peu input params in variables
        $period = $params["period"];
        $properties = $params["properties"];
        $id = $args["id"];
        //put the time parameter in easyer to process way
        $period_range =  substr($period,-1);
        $period_time = (int)substr($period,0,-1);
        if ($period_range == "y")
        { 
            $period_time *= 365;
            $period_range = "d";

        }
        
        //untested
        $new_date = $period_time . $period_range;
        //echo "select pm10,pm25,temperature,humidity FROM sensors WHERE sensor_id = $id AND time > now() - $new_date";
        $result = $database->query("select $properties FROM sensors WHERE sensor_id =~ /$id/ AND time > now() - $new_date");
        //remove time from response
        $decoded = $result->getPoints();
        for ($i = 0;$i < count($decoded);$i++)
        {
            unset($decoded[$i]['time']);
        }
        $response->getBody()->write(json_encode($decoded));
        return $response;
    });
};
