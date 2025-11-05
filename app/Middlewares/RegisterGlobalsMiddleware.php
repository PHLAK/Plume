<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Authors;
use App\Tags;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Views\Twig;

class RegisterGlobalsMiddleware
{
    #[Inject('tags_threshold')]
    private int $tagsThreshold;

    #[Inject('authors_threshold')]
    private int $authorsThreshold;

    public function __construct(
        private Twig $view,
        private Tags $tags,
        private Authors $authors,
    ) {}

    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $twigEnvironment = $this->view->getEnvironment();

        $twigEnvironment->addGlobal('tags_enabled', $this->tagsEnabled());
        $twigEnvironment->addGlobal('authors_enabled', $this->authorsEnabled());

        return $handler->handle($request);
    }

    public function tagsEnabled(): bool
    {
        return $this->tags->count() >= $this->tagsThreshold;
    }

    public function authorsEnabled(): bool
    {
        return $this->authors->count() >= $this->authorsThreshold;
    }
}
