<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required(['MYSQL_DRIVER', 'MYSQL_HOST', 'MYSQL_DATABASE', 'MYSQL_USER', 'MYSQL_PASSWORD']);

return [
  'paths' => [
    'migrations' => 'db/migrations',
    'seeds' => 'db/seeds'
  ],
  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_database' => 'dev',
    'dev' => [
      'adapter' => getenv('MYSQL_DRIVER'),
      'host' => getenv('MYSQL_HOST'),
      'name' => getenv('MYSQL_DATABASE'),
      'user' => getenv('MYSQL_USER'),
      'pass' => getenv('MYSQL_PASSWORD'),
      'port' => 3306
    ]
  ]
];
