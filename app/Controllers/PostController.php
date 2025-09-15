<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Actions\PostFromSlug;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class PostController
{
    public function __construct(
        private PostFromSlug $postFromSlug,
        private Twig $view,
    ) {}

    public function __invoke(Request $request, Response $response, string $slug): ResponseInterface
    {
        $post = ($this->postFromSlug)($slug); // TODO: Cache the Post object (possibly via a decorator), keyed by $slug

        return $this->view->render($response, 'post.twig', ['post' => $post]);
    }
}
