<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\PageController;
use App\Data\Page;
use App\Exceptions\NotFoundException;
use App\Pages;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Tests\TestCase;

#[CoversClass(PageController::class)]
class PageControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_page_by_slug(): void
    {
        $pages = $this->mock(Pages::class);
        $pages->expects($this->once())->method('get')->with('test-page')->willReturn(
            $page = new Page(
                title: 'Test Page',
                link: 'Test',
                body: 'Page body...',
                weight: 0,
            )
        );

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse = new Response, 'page.twig', [
            'page' => $page,
        ])->willReturn(
            $testResponse->withStatus(StatusCodeInterface::STATUS_OK)
        );

        $response = $this->container->call(PageController::class, [
            'response' => $testResponse,
            'slug' => 'test-page',
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }

    #[Test]
    public function it_returns_an_error_when_fetching_a_non_existent_page(): void
    {
        $pages = $this->mock(Pages::class);
        $pages->expects($this->once())->method('get')->with('test-page')->willThrowException(
            new NotFoundException
        );

        $testResponse = (new Response)->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse, 'error.twig', [
            'message' => 'Page not found',
        ])->willReturn($testResponse);

        $response = $this->container->call(PageController::class, [
            'response' => $testResponse,
            'slug' => 'test-page',
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
