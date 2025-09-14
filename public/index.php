<?php

declare(strict_types=1);

use App\Bootstrap;
use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

// Initialize environment variable handler
Dotenv::createUnsafeImmutable(dirname(__DIR__))->safeLoad();

// Create the application
$app = Bootstrap::createApplication(
    dirname(__DIR__) . '/config',
    dirname(__DIR__) . '/cache'
);

// Engage!
$app->run();
