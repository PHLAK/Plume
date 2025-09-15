<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Posts;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class IndexController
{
    public function __construct(
        private Posts $posts,
        private Twig $view,
    ) {}

    public function __invoke(Response $response): ResponseInterface
    {
        return $this->view->render($response, 'index.twig', [
            'posts' => $this->posts->all(),
        ]);
    }
}
