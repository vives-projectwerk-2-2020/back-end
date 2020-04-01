<?php
declare(strict_types=1);

use Monolog\Logger;

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    $dotenv->required(['MYSQL_DRIVER', 'MYSQL_HOST', 'MYSQL_DATABASE', 'MYSQL_USER', 'MYSQL_PASSWORD']);
} finally {
    echo "no .env file in this container"; 
}

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'db' => [
                'driver' => getenv('MYSQL_DRIVER'),
                'host' => getenv('MYSQL_HOST'),
                'database' => getenv('MYSQL_DATABASE'),
                'username' => getenv('MYSQL_USER'),
                'password' => getenv('MYSQL_PASSWORD'),
            ],
            'logger' => [
                'name' => 'Particula',
                'path' => 'php://stderr',
                'level' => Logger::DEBUG,
                'logfile' => __DIR__.'/../logs/server.log',
            ],
        ],
    ]);
};
