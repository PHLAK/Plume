<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Posts;
use App\Utilities\Paginator;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class PostsController
{
    #[Inject('posts_per_page')]
    private int $postsPerPage;

    public function __construct(
        private Posts $posts,
        private Twig $view,
        private Paginator $paginator,
    ) {}

    public function __invoke(Response $response, int $page = 1): ResponseInterface
    {
        $posts = $this->posts->all();

        if ($posts->isEmpty()) {
            return $this->view->render($response, 'error.twig', [
                'message' => 'No posts found',
            ]);
        }

        return $this->view->render($response, 'posts.twig', [
            'posts' => $posts->forPage($page, $this->postsPerPage),
            'paginator' => $this->paginator->of($posts)->page($page),
        ]);
    }
}
