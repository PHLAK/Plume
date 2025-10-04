<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\FeedController;
use App\Posts;
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Tests\TestCase;

#[CoversClass(FeedController::class)]
class FeedControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_successful_response(): void
    {
        $this->container->set('site_title', 'Test site; please ignore');
        $this->container->set('meta_description', 'This is a test site description.');

        $posts = $this->mock(Posts::class);
        $posts->expects($this->once())->method('all')->willReturn(new Collection);

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse = new Response, 'feed.twig', [
            'title' => 'Test site; please ignore',
            'description' => 'This is a test site description.',
            'posts' => new Collection,
        ])->willReturn(
            $testResponse->withStatus(StatusCodeInterface::STATUS_OK)
        );

        $controller = $this->container->get(FeedController::class);

        $response = $controller($testResponse);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }
}
