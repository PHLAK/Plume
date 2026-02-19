<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\TagsController;
use App\Tags;
use Fig\Http\Message\StatusCodeInterface;
use Generator;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Tests\TestCase;

#[CoversClass(TagsController::class)]
class TagsControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_list_of_post_tags(): void
    {
        $posts = $this->mock(Tags::class);
        $posts->expects($this->once())->method('withCount')->willReturn(
            $tags = new LazyCollection(fn (): Generator => yield from ['Foo' => 3, 'Bar' => 5, 'Baz' => 2])
        );

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse = new Response, 'tags.twig', [
            'tags' => $tags,
        ])->willReturn(
            $testResponse->withStatus(StatusCodeInterface::STATUS_OK)
        );

        $response = $this->container->call(TagsController::class, [
            'response' => $testResponse,
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }

    #[Test]
    public function it_shows_an_error_page_when_no_tags_are_found(): void
    {
        $posts = $this->mock(Tags::class);
        $posts->expects($this->once())->method('withCount')->willReturn(new LazyCollection);

        $testResponse = (new Response)->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse, 'error.twig', [
            'message' => 'No tags found',
        ])->willReturn($testResponse);

        $response = $this->container->call(TagsController::class, [
            'response' => $testResponse,
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
