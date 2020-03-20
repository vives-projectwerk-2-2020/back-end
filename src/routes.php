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
        $result = $database->query('select * from sensors');
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
        if ($period_time == "y")
        { 
            $period_time *= 365;
        }
        //untested
        $new_date = $period_time . $period_range;
        $result = $database->query("select pm10,pm25,temperature,humidity FROM WHERE sensor_id = $id AND time > now() - $new_date");
        $response->getBody()->write(json_encode($result->getPoints()));
        return $response;
    });
    $app->get('/sensors', function($request, $response)
    {
        $client = new InfluxDB\Client(getenv('INFLUX_IP'), getenv('INFLUX_PORT'));
        $database = $client->selectDB('particulaInfluxDB');
        $result = $database->query('select pm10,sensor_id FROM sensors');
        $decoded = $result->getPoints();
        //remove valies that dont neet to get send
        for ($i = 0;$i < count($decoded);$i++)
        {
            unset($decoded[$i]['pm10']);
            unset($decoded[$i]['time']);
        }
        // foreach($decoded as $id => $row) {
        //     echo $row['pm10'];
        //     unset($row['pm10']);
        // }
        $response->getBody()->write(json_encode($decoded));
        return $response;
    });
};
