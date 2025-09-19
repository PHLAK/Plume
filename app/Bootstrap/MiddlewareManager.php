<?php

declare(strict_types=1);

namespace App\Bootstrap;

use DI\Attribute\Inject;
use DI\Container;
use Slim\App;
use Slim\Views\Twig;

class MiddlewareManager
{
    #[Inject('middlewares')]
    private array $middlewares;

    /** @param App<Container> $app */
    public function __construct(
        private App $app,
        private Twig $twig,
    ) {}

    /** Register application middlewares. */
    public function __invoke(): void
    {
        foreach ($this->middlewares as $middleware) {
            $this->app->add($middleware);
        }
    }
}
