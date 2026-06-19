<?php

declare(strict_types=1);

namespace App\Controllers;

use DI\Attribute\Inject;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use YetiSearch\YetiSearch;

class SearchController
{
    #[Inject(YetiSearch::class)]
    private YetiSearch $search;

    /** @var array<string, mixed> */
    private array $searchOptions = ['highlight_length' => 50, 'limit' => 8];

    public function __construct(
        #[Inject('fuzzy_search')] bool|string $fuzzySearch
    ) {
        $this->searchOptions['fuzzy'] = (bool) filter_var($fuzzySearch, FILTER_VALIDATE_BOOLEAN);
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        if (! array_key_exists('q', $queryParams) || empty(trim($queryParams['q']))) {
            return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
        }

        /** @var string $query */
        ['q' => $query] = $queryParams;

        /** @var list<array<string, mixed>> $postResults */
        ['results' => $postResults] = $this->search->search('posts', $query, $this->searchOptions);

        /** @var list<array<string, mixed>> $pageResults */
        ['results' => $pageResults] = $this->search->search('pages', $query, $this->searchOptions);

        $allResults = [...$postResults, ...$pageResults];

        usort($allResults, static fn (array $a, array $b): int => $b['score'] <=> $a['score']);

        $results = array_slice($allResults, 0, 8);

        return $response->withHeader('Content-Type', 'application/json')->withBody(
            (new StreamFactory)->createStream(json_encode($results, flags: JSON_THROW_ON_ERROR))
        );
    }
}
