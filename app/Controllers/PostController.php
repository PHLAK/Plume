<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Posts;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class PostController
{
    public function __construct(
        private Posts $posts,
        private Twig $view,
    ) {}

    public function __invoke(Request $request, Response $response, string $slug): ResponseInterface
    {
        return $this->view->render($response, 'post.twig', [
            'post' => $this->posts->get($slug),
        ]);
    }
}
