<?php

declare(strict_types=1);

use App\Factories;
use App\Middlewares;
use App\ViewFunctions;

use function DI\factory;
use function DI\string;

return [
    // -------------------------------------------------------------------------
    // Path definitions
    // -------------------------------------------------------------------------

    'base_path' => dirname(__DIR__),
    'app_path' => string('{base_path}/app'),
    'cache_path' => string('{base_path}/cache'),
    'config_path' => string('{base_path}/config'),
    'resources_path' => string('{base_path}/resources'),
    'posts_path' => string('{base_path}/posts'),
    'public_path' => string('{base_path}/public'),
    'assets_path' => string('{public_path}/assets'),
    'manifest_path' => string('{assets_path}/manifest.json'),
    'views_path' => string('{resources_path}/views'),

    // -------------------------------------------------------------------------
    // Application middlewares
    // -------------------------------------------------------------------------

    'middlewares' => [
        // Middlewares\WhoopsMiddleware::class,
        // Middlewares\PruneCacheMiddleware::class,
        // Middlewares\CacheControlMiddleware::class,
        // Middlewares\RegisterGlobalsMiddleware::class,
    ],

    // -------------------------------------------------------------------------
    // View functions
    // -------------------------------------------------------------------------

    'view_functions' => [
        ViewFunctions\Config::class,
        ViewFunctions\Scripts::class,
        // ViewFunctions\Markdown::class,
        // ViewFunctions\Translate::class,
        ViewFunctions\Vite::class,
    ],

    // -------------------------------------------------------------------------
    // Container definitions
    // -------------------------------------------------------------------------

    // Symfony\Contracts\Cache\CacheInterface::class => factory(Factories\CacheFactory::class),
    // Symfony\Contracts\Translation\TranslatorInterface::class => factory(Factories\TranslationFactory::class),
    Slim\Views\Twig::class => factory(Factories\TwigFactory::class),
    // Whoops\RunInterface::class => create(Whoops\Run::class),
];
