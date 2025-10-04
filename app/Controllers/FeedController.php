<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Posts;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class FeedController
{
    #[Inject('site_title')]
    private string $title;

    #[Inject('meta_description')]
    private string $description;

    public function __construct(
        private Posts $posts,
        private Twig $view,
    ) {}

    public function __invoke(Response $response): ResponseInterface
    {
        return $this->view->render($response, 'feed.twig', [
            'title' => $this->title,
            'description' => $this->description,
            'posts' => $this->posts->all(),
        ]);
    }
}
