<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config;
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

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $posts = $this->posts->all();
        $page = max(1, (int) $request->getQueryParams()['page'] ?? 1);
        $perPage = $this->config->integer('posts_per_page');
        $pageCount = (int) ceil($posts->count() / $perPage);

        return $this->view->render($response, 'index.twig', [
            'posts' => $posts->when(
                $this->config->boolean('pagination'),
                fn (Collection $posts): Collection => $posts->forPage($page, $perPage)
            ),
            'pagination' => [ // TODO: Convert this to a data object
                'current' => $page,
                'previous' => ($previous = $page - 1) >= 1 ? $previous : null,
                'next' => ($next = $page + 1) <= $pageCount ? $next : null,
                'total_pages' => $pageCount,
                // TODO: Show ellipsis (…) when we don't show the full page range
                'pages' => range(
                    max($page - 5, 1),
                    min($page + 5, $pageCount)
                ),
            ],
        ]);
    }
}
