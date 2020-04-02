<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends AppController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, $args)
    {
        $logger = $this->logger;
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
    }
}
