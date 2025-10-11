<?php

declare(strict_types=1);

use App\Decorators;
use App\Factories;
use App\Managers;
use App\Middlewares;
use App\ViewFunctions;

use function DI\create;
use function DI\factory;
use function DI\get;
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
    'build_path' => string('{public_path}/build'),
    'assets_path' => string('{build_path}/assets'),
    'manifest_path' => string('{build_path}/manifest.json'),
    'views_path' => string('{resources_path}/views'),
    'customizations_path' => string('{base_path}/customizations'),
    'scripts_file' => string('{customizations_path}/scripts'),

    // -------------------------------------------------------------------------
    // Application managers
    // -------------------------------------------------------------------------

    'managers' => [
        Managers\MiddlewareManager::class,
        // Managers\ExceptionManager::class,
        Managers\RouteManager::class,
    ],

    // -------------------------------------------------------------------------
    // Application middlewares
    // -------------------------------------------------------------------------

    'middlewares' => [
        Middlewares\WhoopsMiddleware::class,
        // Middlewares\PruneCacheMiddleware::class,
        // Middlewares\CacheControlMiddleware::class,
        // Middlewares\RegisterGlobalsMiddleware::class,
        function (Slim\App $app, Slim\Views\Twig $twig): Slim\Views\TwigMiddleware {
            return Slim\Views\TwigMiddleware::create($app, $twig);
        },
    ],

    // -------------------------------------------------------------------------
    // View functions
    // -------------------------------------------------------------------------

    'view_functions' => [
        ViewFunctions\Config::class,
        ViewFunctions\Scripts::class,
        ViewFunctions\Markdown::class,
        // ViewFunctions\Translate::class,
        ViewFunctions\Vite::class,
    ],

    // -------------------------------------------------------------------------
    // Container definitions
    // -------------------------------------------------------------------------

    App\Posts::class => get(Decorators\CachedPosts::class),
    App\Tags::class => get(Decorators\CachedTags::class),
    League\CommonMark\ConverterInterface::class => factory(Factories\ConverterFactory::class),
    Symfony\Contracts\Cache\CacheInterface::class => factory(Factories\CacheFactory::class),
    // Symfony\Contracts\Translation\TranslatorInterface::class => factory(Factories\TranslationFactory::class),
    Slim\Views\Twig::class => factory(Factories\TwigFactory::class),
    Whoops\RunInterface::class => create(Whoops\Run::class),
];
