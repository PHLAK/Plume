<?php

declare(strict_types=1);

use App\Commands;
use App\Decorators;
use App\Factories;
use App\Filters;
use App\Functions;
use App\Managers;
use App\Middlewares;
use DI\Container;

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
    'public_path' => string('{base_path}/public'),
    'resources_path' => string('{base_path}/resources'),
    'icons_path' => string('{resources_path}/icons'),
    'views_path' => string('{resources_path}/views'),
    'build_path' => string('{public_path}/build'),
    'assets_path' => string('{build_path}/assets'),
    'manifest_path' => string('{build_path}/manifest.json'),

    // User generated data and file paths
    'data_path' => string('{base_path}/data'),
    'posts_path' => string('{data_path}/posts'),
    'pages_path' => string('{data_path}/pages'),
    'scripts_file' => string('{data_path}/scripts'),

    // -------------------------------------------------------------------------
    // Application commands
    // -------------------------------------------------------------------------

    'commands' => [
        Commands\PublishPost::class,
        Commands\PublishPosts::class,
    ],

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
        Middlewares\PruneCacheMiddleware::class,
        // Middlewares\CacheControlMiddleware::class,
        Middlewares\RegisterGlobalsMiddleware::class,
        function (Slim\App $app, Slim\Views\Twig $twig): Slim\Views\TwigMiddleware {
            return Slim\Views\TwigMiddleware::create($app, $twig);
        },
    ],

    // -------------------------------------------------------------------------
    // View filters & functions
    // -------------------------------------------------------------------------

    'view_filters' => [
        Filters\Markdown::class,
    ],

    'view_functions' => [
        Functions\Config::class,
        Functions\Scripts::class,
        Functions\Pages::class,
        Functions\Svg::class,
        Functions\Vite::class,
    ],

    // -------------------------------------------------------------------------
    // Container bindings
    // -------------------------------------------------------------------------

    App\Pages::class => get(Decorators\CachedPages::class),
    App\Posts::class => get(Decorators\CachedPosts::class),
    App\Tags::class => get(Decorators\CachedTags::class),
    Symfony\Component\Console\Application::class => factory(Factories\ConsoleAppFactory::class),
    League\CommonMark\ConverterInterface::class => factory(Factories\ConverterFactory::class),
    Symfony\Contracts\Cache\CacheInterface::class => factory(Factories\CacheFactory::class),
    Slim\Views\Twig::class => factory(Factories\TwigFactory::class),
    Whoops\RunInterface::class => create(Whoops\Run::class),

    'commonmark_config' => [
        'alert' => ['icons' => ['active' => true, 'use_svg' => true]],
    ],

    'tags_enabled' => function (Container $container, App\Tags $tags): bool {
        $tagsLink = (bool) filter_var($container->get('tags_link'), FILTER_VALIDATE_BOOLEAN);

        return $tagsLink && $tags->count() > 1;
    },

    'authors_enabled' => function (Container $container, App\Authors $authors): bool {
        $authorsLink = (bool) filter_var($container->get('authors_link'), FILTER_VALIDATE_BOOLEAN);

        return $authorsLink && $authors->count() > 1;
    },

];
