<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Actions\PostsCollection;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class IndexController
{
    public function __construct(
        private PostsCollection $postsCollection,
        private Twig $view,
    ) {}

    public function __invoke(Response $response): ResponseInterface
    {
        $posts = ($this->postsCollection)(); // TODO: Cache this (possibly via a decorator)

        return $this->view->render($response, 'index.twig', [
            'posts' => $posts,
        ]);
    }
}
