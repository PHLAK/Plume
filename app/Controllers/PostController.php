<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\PostNotFoundException;
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
        try {
            $post = $this->posts->get($slug);
        } catch (PostNotFoundException) {
            return $this->view->render($response->withStatus(404), 'error.twig', [
                'message' => 'Post not found',
            ]);
        }

        if ($post->draft || $post->published->isFuture()) {
            return $this->view->render($response->withStatus(404), 'error.twig', [
                'message' => 'Post not found',
            ]);
        }

        return $this->view->render($response, 'post.twig', ['post' => $post]);
    }
}
