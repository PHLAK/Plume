<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\SearchController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;
use Tests\TestCase;
use YetiSearch\YetiSearch;

#[CoversClass(SearchController::class)]
class SearchControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_404_error_when_the_query_is_missing(): void
    {
        $search = $this->mock(YetiSearch::class);
        $search->expects($this->never())->method('search');

        /** @var Response $result */
        $result = $this->container->call(SearchController::class, [
            'request' => (new ServerRequestFactory)->createServerRequest('GET', '/search'),
            'response' => new Response,
        ]);

        $this->assertSame(404, $result->getStatusCode());
    }

    #[Test]
    public function it_returns_404_when_the_query_is_empty(): void
    {
        $search = $this->mock(YetiSearch::class);
        $search->expects($this->never())->method('search');

        /** @var Response $result */
        $result = $this->container->call(SearchController::class, [
            'request' => (new ServerRequestFactory)->createServerRequest('GET', '/search')->withQueryParams(['q' => ' ']),
            'response' => new Response,
        ]);

        $this->assertSame(404, $result->getStatusCode());
    }

    #[Test]
    public function it_returns_a_response_for_a_valid_query(): void
    {
        $search = $this->mock(YetiSearch::class);

        $search->expects($this->exactly(2))->method('search')->willReturnMap([
            ['posts', 'test', ['highlight_length' => 50, 'limit' => 8], [
                'results' => [
                    ['id' => 'test-post', 'score' => 0.9, 'metadata' => ['url' => '/post/test-post']],
                ],
            ]],
            ['pages', 'test', ['highlight_length' => 50, 'limit' => 8], [
                'results' => [
                    ['id' => 'test-page', 'score' => 0.7, 'metadata' => ['url' => '/pages/test-page']],
                ],
            ]],
        ]);

        /** @var Response $result */
        $result = $this->container->call(SearchController::class, [
            'request' => (new ServerRequestFactory)->createServerRequest('GET', '/search')->withQueryParams(['q' => 'test']),
            'response' => new Response,
        ]);

        $this->assertSame(200, $result->getStatusCode());
        $this->assertSame('application/json', $result->getHeaderLine('Content-Type'));
        $this->assertSame(json_encode([
            ['id' => 'test-post', 'score' => 0.9, 'metadata' => ['url' => '/post/test-post']],
            ['id' => 'test-page', 'score' => 0.7, 'metadata' => ['url' => '/pages/test-page']],
        ]), (string) $result->getBody());
    }
}
