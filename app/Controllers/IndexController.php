<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config;
use App\Data\Paginator;
use App\Posts;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class IndexController
{
    public function __construct(
        private Config $config,
        private Posts $posts,
        private Twig $view,
    ) {}

    public function __invoke(Request $request, Response $response, int $page = 1): ResponseInterface
    {
        $posts = $this->posts->all();

        $paginator = new Paginator($posts, $this->config->integer('posts_per_page'), $page);

        if ($page > $paginator->pages) {
            return $response->withHeader('Location', sprintf('/%d', $paginator->pages))->withStatus(302);
        }

        return $this->view->render($response, 'index.twig', [
            'posts' => $posts->when(
                $this->config->boolean('pagination'),
                fn (Collection $posts): Collection => $posts->forPage($page, $paginator->perPage)
            ),
            'pagination' => $paginator,
        ]);
    }
}
