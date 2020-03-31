<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Sensor;
use App\Models\User;

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

        if (mysql_num_rows($sensors) > 0) {
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

        if (mysql_num_rows($users) > 0) {
            $jsontext = substr_replace($jsontext, '', -1);
        }
        $jsontext .= "]";

        $response->getBody()->write($jsontext);
        return $response;
    });
};
