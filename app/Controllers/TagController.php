<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Data\Paginator;
use App\Posts;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class TagController
{
    #[Inject('pagination')]
    private bool $pagination;

    #[Inject('posts_per_page')]
    private int $postsPerPage;

    public function __construct(
        private Posts $posts,
        private Twig $view,
    ) {}

    public function __invoke(Request $request, Response $response, string $tag, int $page = 1): ResponseInterface
    {
        $posts = $this->posts->withTag($tag);
        $paginator = $this->pagination ? new Paginator($posts, $this->postsPerPage, $page) : null;

        return $this->view->render($response, 'index.twig', [
            'posts' => $posts,
            'pagination' => $paginator,
        ]);
    }
}
