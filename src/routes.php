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
    $app->post('/addsensor', SensorController::class . ':create');
    $app->get('/sensors', SensorController::class . ':index');
    $app->put('/sensors/{id}', SensorController::class . ':edit');
    $app->delete('/sensors/{id}', SensorController::class . ':delete');

    // User routes
    $app->get('/users', UserController::class . ':index');
    $app->get('/users/{username}', UserController::class . ':details');

    $app->post('/adduser', UserController::class . ':create');

    $app->delete('/users/{username}', UserController::class . ':delete');

    $app->put('/users/{username}', UserController::class . ':update');

};
