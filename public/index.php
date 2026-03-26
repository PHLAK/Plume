<?php

declare(strict_types=1);

use App\Bootstrap\Builder;
use Dotenv\Dotenv;
use Slim\App;

// Import the autoloader
require dirname(__DIR__) . '/vendor/autoload.php';

// Initialize environment variable handler
Dotenv::createUnsafeImmutable(dirname(__DIR__))->safeLoad();

// Create the DI container
$container = Builder::createContainer(
    dirname(__DIR__) . '/config',
    dirname(__DIR__) . '/cache'
);

// Create the application
$app = $container->get(App::class);

// Engage!
$app->run();
