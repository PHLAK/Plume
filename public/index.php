<?php

declare(strict_types=1);

use App\Bootstrapper;
use Dotenv\Dotenv;

// Import the autoloader
require dirname(__DIR__) . '/vendor/autoload.php';

// Initialize environment variable handler
Dotenv::createUnsafeImmutable(dirname(__DIR__))->safeLoad();

// Create the DI container
$container = Bootstrapper::createContainer(
    dirname(__DIR__) . '/config',
    dirname(__DIR__) . '/cache'
);

// Create the application
$app = Bootstrapper::createApp($container);

// Engage!
$app->run();
