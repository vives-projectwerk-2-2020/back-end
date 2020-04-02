<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\User;

class UserController extends AppController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, $args)
    {
        $users = User::all();
        $response->getBody()->write(json_encode($users));
        return $response;
    }

    public function create(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $sensor = User::create($data);
        $sensor->save();
        return $response;
    }

    public function details(Request $request, Response $response, $args)
    {
        $users = User::where('UserName', $args["username"])
            ->get();
        $jsontext = "[";
        foreach ($users as $user) {
            $user_json = json_encode(array("username"=>$user->UserName
                , "password"=>$user->UserPassword, "email"=>$user->Email));
            $jsontext .= $user_json . ",";
        }
        if ($users->count() > 0) {
            $jsontext = substr_replace($jsontext, '', -1);
        }
        $jsontext .= "]";
        $response->getBody()->write($jsontext);
        return $response;
    }
}
