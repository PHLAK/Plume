<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Controllers;
use DI\Container;
use Slim\App;

class RouteManager
{
    /** @param App<Container> $app */
    public function __construct(
        private App $app
    ) {}

    public function __invoke(): void
    {
        $this->app->get('/[{page:[0-9]+}]', Controllers\IndexController::class)->setName('posts');
        $this->app->get('/post/{slug}', Controllers\PostController::class)->setName('post');
        $this->app->get('/tag/{tag}[/{page:[0-9]+}]', Controllers\TagController::class)->setName('tag');
        $this->app->get('/feed', Controllers\FeedController::class)->setName('feed');
    }
}
