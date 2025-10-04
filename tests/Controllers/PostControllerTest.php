<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\PostController;
use App\Data\Post;
use App\Exceptions\PostNotFoundException;
use App\Posts;
use Carbon\Carbon;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Tests\TestCase;

#[CoversClass(PostController::class)]
class PostControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_post_by_slug(): void
    {
        $posts = $this->mock(Posts::class);
        $posts->expects($this->once())->method('get')->with('test-post')->willReturn(
            $post = new Post(
                title: 'Test Post',
                body: 'Post body...',
                published: Carbon::now()->subDay(),
            )
        );

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse = new Response, 'post.twig', [
            'post' => $post,
        ])->willReturn(
            $testResponse->withStatus(StatusCodeInterface::STATUS_OK)
        );

        $response = $this->container->call(PostController::class, [
            'response' => $testResponse,
            'slug' => 'test-post',
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }

    #[Test]
    public function it_returns_an_error_when_fetching_a_non_existent_post(): void
    {
        $posts = $this->mock(Posts::class);
        $posts->expects($this->once())->method('get')->with('test-post')->willThrowException(
            new PostNotFoundException
        );

        $testResponse = (new Response)->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse, 'error.twig', [
            'message' => 'Post not found',
        ])->willReturn($testResponse);

        $response = $this->container->call(PostController::class, [
            'response' => $testResponse,
            'slug' => 'test-post',
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }

    #[Test]
    public function it_returns_an_error_when_fetching_a_future_post(): void
    {
        $posts = $this->mock(Posts::class);
        $posts->expects($this->once())->method('get')->with('test-post')->willReturn(
            $post = new Post(
                title: 'Test Post',
                body: 'Post body...',
                published: Carbon::now()->addDay(),
            )
        );

        $testResponse = (new Response)->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse, 'error.twig', [
            'message' => 'Post not found',
        ])->willReturn($testResponse);

        $response = $this->container->call(PostController::class, [
            'response' => $testResponse,
            'slug' => 'test-post',
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }

    #[Test]
    public function it_returns_an_error_when_fetching_a_draft_post(): void
    {
        $posts = $this->mock(Posts::class);
        $posts->expects($this->once())->method('get')->with('test-post')->willReturn(
            $post = new Post(
                title: 'Test Post',
                body: 'Post body...',
                published: Carbon::now()->subDay(),
                draft: true
            )
        );

        $testResponse = (new Response)->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse, 'error.twig', [
            'message' => 'Post not found',
        ])->willReturn($testResponse);

        $response = $this->container->call(PostController::class, [
            'response' => $testResponse,
            'slug' => 'test-post',
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
