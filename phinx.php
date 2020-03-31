<?php

require __DIR__ . '/vendor/autoload.php';

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();
// $dotenv->required(['MYSQL_DRIVER', 'MYSQL_HOST', 'MYSQL_DATABASE', 'MYSQL_USER', 'MYSQL_PASSWORD']);

return [
  'paths' => [
    'migrations' => 'db/migrations',
    'seeds' => 'db/seeds'
  ],
  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_database' => 'dev',
    'dev' => [
      'adapter' => $_ENV['MYSQL_DRIVER'],
      'host' => $_ENV['MYSQL_HOST'],
      'name' => $_ENV['MYSQL_DATABASE'],
      'user' => $_ENV['MYSQL_USER'],
      'pass' => $_ENV['MYSQL_PASSWORD'],
      'port' => 3306
    ]
  ]
];
