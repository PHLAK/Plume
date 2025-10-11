<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\TagController;
use App\Data\Paginator;
use App\Data\Post;
use App\Posts;
use Carbon\Carbon;
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Tests\TestCase;

#[CoversClass(TagController::class)]
class TagControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_list_of_posts_for_a_given_tag(): void
    {
        $this->container->set('posts_per_page', $perPage = 10);

        $posts = $this->mock(Posts::class);
        $posts->expects($this->once())->method('withTag')->with('Foo')->willReturn(
            $postsCollection = Collection::times(10, fn (int $iteration): Post => new Post(
                title: sprintf('Test Post %d', $iteration),
                body: 'Post body...',
                published: Carbon::now()->subDays($iteration),
                tags: ['Foo'],
            ))
        );

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse = new Response, 'posts.twig', [
            'posts' => $postsCollection->forPage(1, $perPage),
            'pagination' => new Paginator($postsCollection, $perPage, 1),
        ])->willReturn(
            $testResponse->withStatus(StatusCodeInterface::STATUS_OK)
        );

        $response = $this->container->call(TagController::class, [
            'response' => $testResponse,
            'tag' => 'Foo',
            // 'page' => $page,
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }
}
