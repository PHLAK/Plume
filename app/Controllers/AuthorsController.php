<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Authors;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class AuthorsController
{
    public function __construct(
        private Authors $authors,
        private Twig $view,
    ) {}

    public function __invoke(Response $response): ResponseInterface
    {
        $authors = $this->authors->withCount();

        if ($authors->isEmpty()) {
            return $this->view->render($response, 'error.twig', [
                'message' => 'No authors found',
            ]);
        }

        return $this->view->render($response, 'authors.twig', [
            'authors' => $authors->sortKeys(),
        ]);
    }
}
