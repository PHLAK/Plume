<?php

declare(strict_types=1);

namespace App\Controllers;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use YetiSearch\YetiSearch;

class SearchController
{
    #[Inject(YetiSearch::class)]
    private YetiSearch $search;

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        if (! array_key_exists('q', $queryParams)) {
            return $response->withStatus(422);
        }

        /** @var string $query */
        ['q' => $query] = $queryParams;

        /** @var list<array<string, mixed>> $postResults */
        ['results' => $postResults] = $this->search->search('posts', $query, ['fuzzy' => true, 'limit' => 8]);

        /** @var list<array<string, mixed>> $pageResults */
        ['results' => $pageResults] = $this->search->search('pages', $query, ['fuzzy' => true, 'limit' => 8]);

        $allResults = [...$postResults, ...$pageResults];

        usort($allResults, static fn (array $a, array $b): int => $b['score'] <=> $a['score']);

        $results = array_slice($allResults, 0, 8);

        return $response->withHeader('Content-Type', 'application/json')->withBody(
            (new StreamFactory)->createStream(json_encode($results, flags: JSON_THROW_ON_ERROR))
        );
    }
}
