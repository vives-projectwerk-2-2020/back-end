<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;

/*
 * AppController is the main Controller that every other controller
 * can inherit from. This makes it possible to share common behaviour
 * (code) between all Controllers and thus the whole application.
 *
 * This keeps the code DRY ;-)
 */

class AppController
{

    protected $logger;
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $container->get(LoggerInterface::class);
    }
}
