<?php

declare(strict_types=1);

namespace App\Middlewares;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Views\Twig;

class RegisterGlobalsMiddleware
{
    #[Inject('authors_enabled')]
    private bool $authorsEnabled;

    #[Inject('tags_enabled')]
    private bool $tagsEnabled;

    public function __construct(
        private Twig $view,
    ) {}

    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $twigEnvironment = $this->view->getEnvironment();

        $twigEnvironment->addGlobal('authors_enabled', $this->authorsEnabled);
        $twigEnvironment->addGlobal('tags_enabled', $this->tagsEnabled);

        return $handler->handle($request);
    }
}
