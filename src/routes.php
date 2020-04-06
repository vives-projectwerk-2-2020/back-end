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
    $app->post('/sensors', SensorController::class . ':create');
    $app->put('/sensors/{id}', SensorController::class . ':update');
    $app->delete('/sensors/{id}', SensorController::class . ':delete');

    // User routes
    $app->get('/users', UserController::class . ':index');
    $app->get('/users/{username}', UserController::class . ':details');
    $app->post('/users', UserController::class . ':create');
    $app->put('/users/{username}', UserController::class . ':update');
    $app->delete('/users/{username}', UserController::class . ':delete');
};
