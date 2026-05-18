<?php

declare(strict_types=1);

namespace App\Controllers;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\StreamFactory;
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
        $queryParams = $request->getQueryParams();

        if (! array_key_exists('q', $queryParams)) {
            return $response->withStatus(422);
        }

        ['q' => $query] = $queryParams;

        ['results' => $results] = $this->search->searchMultiple(['posts', 'pages'], $query, [
            'limit' => 8,
        ]);

        return $response->withHeader('Content-Type', 'application/json')->withBody(
            (new StreamFactory)->createStream(json_encode($results, flags: JSON_THROW_ON_ERROR))
        );
    }
}
