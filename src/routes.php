<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Controllers\HomeController;
use App\Controllers\MeasurementController;
use App\Controllers\SensorController;
use App\Controllers\UserController;

use Slim\App;

return function (App $app) {
    // Home routes
    $app->get('/', HomeController::class . ':index');

    // Testing routes?
    $app->get('/foo', MeasurementController::class . ':getAllMeasurements');

    // Measurement routes
    $app->get('/measurements/{id}', MeasurementController::class . ':getMeasurements');

    // Sensor routes
    $app->get('/sensors', SensorController::class . ':index');
    $app->get('/sensors/{guid}', SensorController::class . ':details');
    $app->post('/sensors', SensorController::class . ':create');
    $app->put('/sensors/{guid}', SensorController::class . ':update');
    $app->delete('/sensors/{guid}', SensorController::class . ':delete');

    // User routes
    $app->get('/users', UserController::class . ':index');
    $app->get('/users/{username}', UserController::class . ':details');
    $app->post('/users', UserController::class . ':create');
    $app->put('/users/{username}', UserController::class . ':update');
    $app->delete('/users/{username}', UserController::class . ':delete');

    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function (Request $request, Response $response) {
            $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
            return $handler($req, $res);
        }
    );
};
