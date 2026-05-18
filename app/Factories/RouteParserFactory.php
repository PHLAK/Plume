<?php

declare(strict_types=1);

namespace App\Factories;

use Slim\App;
use Slim\Interfaces\RouteParserInterface;

class RouteParserFactory
{
    public function __invoke(App $app): RouteParserInterface
    {
        return $app->getRouteCollector()->getRouteParser();
    }
}
