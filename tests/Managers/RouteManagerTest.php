<?php

declare(strict_types=1);

namespace Tests\Managers;

use App\Controllers;
use App\Managers\RouteManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Slim\App;
use Slim\Interfaces\RouteInterface;
use Tests\TestCase;

#[CoversClass(RouteManager::class)]
class RouteManagerTest extends TestCase
{
    private const array ROUTES = [
        ['posts', '/[{page:[0-9]+}]', Controllers\PostsController::class],
        ['post', '/post/{slug}', Controllers\PostController::class],
        ['authors', '/authors', Controllers\AuthorsController::class],
        ['author', '/author/{author}[/{page:[0-9]+}]', Controllers\AuthorController::class],
        ['tags', '/tags', Controllers\TagsController::class],
        ['tag', '/tag/{tag}[/{page:[0-9]+}]', Controllers\TagController::class],
        ['feed', '/feed', Controllers\FeedController::class],
        ['page', '/pages/{slug}', Controllers\PageController::class],
    ];

    #[Test]
    public function it_registers_the_application_routes_redux(): void
    {
        /** @var App&MockObject $app */
        $app = $this->createMock(App::class);

        $app->expects($matcher = $this->atLeast(1))->method('get')->willReturnCallback(
            function (string $pattern, string $controller) use ($matcher): RouteInterface {
                [$expectedName, $expectedPattern, $expectedController] = self::ROUTES[$matcher->numberOfInvocations() - 1];

                $this->assertSame($expectedPattern, $pattern);
                $this->assertSame($expectedController, $controller);

                $routeMock = $this->createMock(RouteInterface::class);
                $routeMock->expects($this->once())->method('setName')->with($expectedName)->willReturnSelf();

                return $routeMock;
            }
        );

        (new RouteManager($app))();
    }
}
