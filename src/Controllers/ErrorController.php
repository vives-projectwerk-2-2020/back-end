<?php

use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorRendererInterface;
use Throwable;

final class ErrorController extends ErrorRendererInterface
{
    public function __invoke(Throwable $exception, bool $displayErrorDetails)
    {
        if ($exception instanceof HttpNotFoundException)
        {
            return json_encode(404);
        }
    }
}