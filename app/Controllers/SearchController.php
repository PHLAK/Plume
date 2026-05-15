<?php

declare(strict_types=1);

namespace App\Controllers;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use YetiSearch\YetiSearch;

class SearchController
{
    #[Inject(YetiSearch::class)]
    private YetiSearch $search;

    public function __construct(
        private Twig $view,
    ) {}

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        ['q' => $query] = $request->getQueryParams();

        ['results' => $results] = $this->search->searchMultiple(['posts', 'pages'], $query);

        return $this->view->render($response, 'search.twig', [
            'results' => $results,
        ]);
    }
}
