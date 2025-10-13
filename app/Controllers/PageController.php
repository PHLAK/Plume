<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\PostNotFoundException;
use App\Pages;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class PageController
{
    public function __construct(
        private Pages $pages,
        private Twig $view,
    ) {}

    public function __invoke(Response $response, string $slug): ResponseInterface
    {
        try {
            $page = $this->pages->get($slug);
        } catch (PostNotFoundException) {
            return $this->view->render($response->withStatus(404), 'error.twig', [
                'message' => 'Page not found',
            ]);
        }

        return $this->view->render($response, 'page.twig', ['page' => $page]);
    }
}
