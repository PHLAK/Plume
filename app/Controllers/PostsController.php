<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Data\Paginator;
use App\Posts;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class PostsController
{
    #[Inject('pagination')]
    private bool $pagination;

    #[Inject('posts_per_page')]
    private int $postsPerPage;

    public function __construct(
        private Posts $posts,
        private Twig $view,
    ) {}

    public function __invoke(Response $response, int $page = 1): ResponseInterface
    {
        $paginator = $this->pagination ? new Paginator($posts = $this->posts->all(), $this->postsPerPage, $page) : null;

        return $this->view->render($response, 'posts.twig', [
            'posts' => $posts->forPage($page, $this->postsPerPage),
            'pagination' => $paginator,
        ]);
    }
}
