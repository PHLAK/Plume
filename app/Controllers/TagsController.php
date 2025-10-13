<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Tags;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class TagsController
{
    public function __construct(
        private Tags $tags,
        private Twig $view,
    ) {}

    public function __invoke(Response $response): ResponseInterface
    {
        $tags = $this->tags->withCount();

        if ($tags->isEmpty()) {
            return $this->view->render($response, 'error.twig', [
                'message' => 'No tags found',
            ]);
        }

        return $this->view->render($response, 'tags.twig', [
            'tags' => $tags->sortKeys(),
        ]);
    }
}
