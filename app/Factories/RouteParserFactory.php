<?php

declare(strict_types=1);

namespace App\Factories;

use DI\Container;
use Slim\App;
use Slim\Interfaces\RouteParserInterface;

class RouteParserFactory
{
    /** @param App<Container> $app */
    public function __invoke(App $app): RouteParserInterface
    {
        return $app->getRouteCollector()->getRouteParser();
    }
}
