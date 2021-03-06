<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use InfluxDB\Client;

use App\Models\Measurement;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
      Capsule::class => function (ContainerInterface $c) {
          $settings = $c->get('settings');
          $eloquent = new Capsule;
          $eloquent->addConnection($settings['db']);
          // Make this Capsule instance available globally via static methods
          $eloquent->setAsGlobal();
          // Setup the Eloquent ORM...
          $eloquent->bootEloquent();
          // Set the fetch mode to return associative arrays.
          $eloquent->setFetchMode(PDO::FETCH_ASSOC);

          return $eloquent;
      },
      LoggerInterface::class => function (ContainerInterface $c) {
        $settings = $c->get('settings')['logger'];
 
        $logger = new Logger($settings['name']);
        $consoleHandler = new StreamHandler($settings['path'], $settings['level']);
        // $fileHandler = new StreamHandler($settings['logfile'], $settings['level']);
        // $logger->pushHandler($fileHandler);
        $logger->pushHandler($consoleHandler);
 
        return $logger;
      },
      'influxDB' => function (ContainerInterface $c) {
        $settings = $c->get('settings')['influxDB'];
        
        $host = $settings['host'];
        $port = $settings['port'];

        $client = new Client($host, $port);
        $database = $client->selectDB($settings['database']);
        Measurement::setDatabase($database);
        return $database;
      },
    ]);
};
