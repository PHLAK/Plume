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
    'resources_path' => string('{base_path}/resources'),

    // Theme resource paths
    'css_path' => string('{theme_path}/css'),
    'icons_path' => string('{theme_path}/icons'),
    'js_path' => string('{theme_path}/js'),
    'views_path' => string('{theme_path}/views'),

    // Public asset paths
    'public_path' => string('{base_path}/public'),
    'build_path' => string('{public_path}/build'),
    'assets_path' => string('{build_path}/assets'),
    'manifest_path' => string('{build_path}/manifest.json'),

    // User generated data and file paths
    'data_path' => string('{base_path}/data'),
    'posts_path' => string('{data_path}/posts'),
    'pages_path' => string('{data_path}/pages'),
    'themes_path' => string('{base_path}/themes'),
    'customizations_file' => string('{data_path}/customizations.html'),

    // -------------------------------------------------------------------------
    // Application commands
    // -------------------------------------------------------------------------

    'commands' => [
        Commands\Publish::class,
        Commands\PublishPage::class,
        Commands\PublishPages::class,
        Commands\PublishPost::class,
        Commands\PublishPosts::class,
    ],

    // -------------------------------------------------------------------------
    // Application managers
    // -------------------------------------------------------------------------

    'managers' => [
        Managers\MiddlewareManager::class,
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
        Functions\Css::class,
        Functions\Customizations::class,
        Functions\Js::class,
        Functions\Pages::class,
        Functions\Svg::class,
        Functions\Vite::class,
    ],

    // -------------------------------------------------------------------------
    // Configuration bindings
    // -------------------------------------------------------------------------

    'commonmark_config' => [
        'alert' => ['icons' => ['active' => true]],
        'disallowed_raw_html' => ['disallowed_tags' => ['script']],
        'heading_permalink' => ['heading_class' => 'group', 'symbol' => '#'],
        'table_of_contents' => ['position' => 'placeholder', 'placeholder' => '[[TOC]]'],
    ],

    // -------------------------------------------------------------------------
    // Dynamic bindings
    // -------------------------------------------------------------------------

    'tags_enabled' => function (Container $container): bool {
        return (bool) filter_var($container->get('tags_link'), FILTER_VALIDATE_BOOLEAN);
    },

    'authors_enabled' => function (Container $container): bool {
        return (bool) filter_var($container->get('authors_link'), FILTER_VALIDATE_BOOLEAN);
    },

    'theme_path' => function (Container $container): string {
        /** @var string|null $theme */
        $theme = $container->get('theme');

        return $theme ? sprintf('%s/%s', $container->get('themes_path'), $theme) : $container->get('resources_path');
    },

    // -------------------------------------------------------------------------
    // App factories and decorators
    // -------------------------------------------------------------------------

    Slim\App::class => factory(Factories\AppFactory::class),
    Symfony\Component\Console\Application::class => factory(Factories\ConsoleAppFactory::class),
    Symfony\Contracts\Cache\CacheInterface::class => factory(Factories\CacheFactory::class),
    League\CommonMark\ConverterInterface::class => factory(Factories\ConverterFactory::class),
    Slim\Views\Twig::class => factory(Factories\TwigFactory::class),
    Whoops\RunInterface::class => create(Whoops\Run::class),

    App\Authors::class => get(Decorators\CachedAuthors::class),
    App\Pages::class => get(Decorators\CachedPages::class),
    App\Posts::class => get(Decorators\CachedPosts::class),
    App\Tags::class => get(Decorators\CachedTags::class),

];
