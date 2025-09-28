<?php

declare(strict_types=1);

namespace Tests\Managers;

use App\Controllers;
use App\Managers\RouteManager;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Slim\App;
use Slim\Interfaces\RouteInterface;
use Tests\TestCase;

#[CoversClass(RouteManager::class)]
class RouteManagerTest extends TestCase
{
    #[Test]
    public function it_registers_the_application_routes(): void
    {
        /** @var App&MockObject $app */
        $app = $this->createMock(App::class);

        $postsRoute = $this->createMock(RouteInterface::class);
        $postsRoute->expects($this->once())->method('setName')->with('posts');

        $postRoute = $this->createMock(RouteInterface::class);
        $postRoute->expects($this->once())->method('setName')->with('post');

        $tagsRoute = $this->createMock(RouteInterface::class);
        $tagsRoute->expects($this->once())->method('setName')->with('tags');

        $tagRoute = $this->createMock(RouteInterface::class);
        $tagRoute->expects($this->once())->method('setName')->with('tag');

        $feedRoute = $this->createMock(RouteInterface::class);
        $feedRoute->expects($this->once())->method('setName')->with('feed');

        $app->expects($this->exactly(5))->method('get')->willReturnCallback(
            fn (string $path, string $controller): RouteInterface&MockObject => match ([$path, $controller]) {
                ['/[{page:[0-9]+}]', Controllers\PostsController::class] => $postsRoute,
                ['/post/{slug}', Controllers\PostController::class] => $postRoute,
                ['/tags', Controllers\TagsController::class] => $tagsRoute,
                ['/tag/{tag}[/{page:[0-9]+}]', Controllers\TagController::class] => $tagRoute,
                ['/feed', Controllers\FeedController::class] => $feedRoute,
                default => throw new InvalidArgumentException(sprintf('Unexpected route [%s, %s]', $path, $controller)),
            }
        );

        (new RouteManager($app))();
    }
}
