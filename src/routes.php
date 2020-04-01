<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\User;
use App\Models\TodoItem;

use App\Models\Sensor;

use \Curl\Curl;

use Slim\App;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {

        $logger = $this->get('logger');
        $logger->info("********* hello world from logger *********");
        $logger->info("Example log levels below from HIGH to LOW");
        $logger->emergency('Your emergency log message');
        $logger->critical('Your critical log message');
        $logger->error('Your error log message');
        $logger->warning('Your warning log message');
        $logger->notice('Your notice log message');
        $logger->info('Your info log message');
        $logger->debug('Your debug log message');
        $logger->debug('example with extra objects/info', array('username' => 'admin'));


        $time = date('y-m-d\TH:i:s');
        $response->getBody()->write(json_encode($time));
        $test =  substr("qwerty", 0, 2);
        $logger->debug($test);


        return $response;
    });
    $app->get('/foo', function (Request $request, Response $response) {
        $client = new InfluxDB\Client(getenv('INFLUX_IP'), getenv('INFLUX_PORT'));
        $database = $client->selectDB('particulaInfluxDB');
        $result = $database->query('select * from sensors GROUP BY sensor_id');
        $response->getBody()->write(json_encode($result->getPoints()));
        return $response;
    });
    $app->get('/measurements/{id}', function ($request, $response, $args) {
        //make new request for mysql
        $client = new InfluxDB\Client(getenv('INFLUX_IP'), getenv('INFLUX_PORT'));
        $database = $client->selectDB('particulaInfluxDB');
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

        if ($sensors->count() > 0) {
            $jsontext = substr_replace($jsontext, '', -1);
        }
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

    $app->get('/user/{username}', function (Request $request, Response $response, $args) {
        $users = User::where('UserName', $args["username"])
               ->get();

        $jsontext = "[";
        foreach ($users as $user) {
            $user_json = json_encode(array("username"=>$user->UserName, "password"=>$user->UserPassword, "email"=>$user->Email));

            $jsontext .= $user_json . ",";
        }

        if ($users->count() > 0) {
            $jsontext = substr_replace($jsontext, '', -1);
        }
        $jsontext .= "]";

        $response->getBody()->write($jsontext);
        return $response;
    });
};
