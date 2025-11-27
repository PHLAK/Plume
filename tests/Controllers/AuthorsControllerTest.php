<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Authors;
use App\Controllers\AuthorsController;
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Tests\TestCase;

#[CoversClass(AuthorsController::class)]
class AuthorsControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_list_of_post_authors(): void
    {
        $posts = $this->mock(Authors::class);
        $posts->expects($this->once())->method('withCount')->willReturn(
            $tags = new Collection(['Arthur Dent' => 3, 'Ford Prefect' => 5, 'Trisha McMillan' => 2])
        );

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse = new Response, 'authors.twig', [
            'authors' => $tags,
        ])->willReturn(
            $testResponse->withStatus(StatusCodeInterface::STATUS_OK)
        );

        $response = $this->container->call(AuthorsController::class, [
            'response' => $testResponse,
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }

    #[Test]
    public function it_shows_an_error_page_when_no_authors_are_found(): void
    {
        $posts = $this->mock(Authors::class);
        $posts->expects($this->once())->method('withCount')->willReturn(new Collection);

        $testResponse = (new Response)->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse, 'error.twig', [
            'message' => 'No authors found',
        ])->willReturn($testResponse);

        $response = $this->container->call(AuthorsController::class, [
            'response' => $testResponse,
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
