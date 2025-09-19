<?php

declare(strict_types=1);

namespace App;

use App\Bootstrap\MiddlewareManager;
use App\Bootstrap\RouteManager;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use DI\ContainerBuilder;
use Slim\App;

class Bootstrap
{
    /** @return App<Container> */
    public static function createApplication(string $configPath, string $cachePath): App
    {
        /** @var list<string> $configFiles */
        $configFiles = glob($configPath . '/*.php') ?: [];

        $containerBuilder = (new ContainerBuilder)->useAttributes(true)->addDefinitions(...$configFiles);

        if (self::containerCompilationEnabled()) {
            $containerBuilder->enableCompilation($cachePath);
        }

        $container = $containerBuilder->build();

        $app = Bridge::create($container);

        $container->call(MiddlewareManager::class);
        // $container->call(ExceptionManager::class);
        $container->call(RouteManager::class);

        return $app;
    }

    private static function containerCompilationEnabled(): bool
    {
        if (filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOL)) {
            return false;
        }

        $compileContainer = getenv('COMPILE_CONTAINER');

        if ($compileContainer === false) {
            return true;
        }

        return strtolower($compileContainer) !== 'false';
    }
}
