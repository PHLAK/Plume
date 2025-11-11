<?php

declare(strict_types=1);

namespace App\Managers;

use App\Controllers;
use DI\Attribute\Inject;
use DI\Container;
use Slim\App;

class RouteManager
{
    /** @var App<Container> $app */
    #[Inject(App::class)]
    private App $app;

    #[Inject('authors_enabled')]
    private bool $authorsEnabled;

    #[Inject('tags_enabled')]
    private bool $tagsEnabled;

    public function __invoke(): void
    {
        $this->app->get('/[{page:[0-9]+}]', Controllers\PostsController::class)->setName('posts');
        $this->app->get('/post/{slug}', Controllers\PostController::class)->setName('post');
        $this->app->get('/author/{author}[/{page:[0-9]+}]', Controllers\AuthorController::class)->setName('author');
        $this->app->get('/tag/{tag}[/{page:[0-9]+}]', Controllers\TagController::class)->setName('tag');
        $this->app->get('/feed', Controllers\FeedController::class)->setName('feed');
        $this->app->get('/pages/{slug}', Controllers\PageController::class)->setName('page');

        if ($this->authorsEnabled) {
            $this->app->get('/authors', Controllers\AuthorsController::class)->setName('authors');
        }

        if ($this->tagsEnabled) {
            $this->app->get('/tags', Controllers\TagsController::class)->setName('tags');
        }
    }
}
