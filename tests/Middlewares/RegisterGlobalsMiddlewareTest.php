<?php

declare(strict_types=1);

namespace Tests\Middlewares;

use App\Middlewares\RegisterGlobalsMiddleware;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;
use Tests\TestCase;

#[CoversClass(RegisterGlobalsMiddleware::class)]
class RegisterGlobalsMiddlewareTest extends TestCase
{
    private Twig $view;

    protected function setUp(): void
    {
        parent::setUp();

        $this->view = $this->container->get(Twig::class);
    }

    public static function configurationProvider(): array
    {
        return [
            [true, true],
            [true, false],
            [false, true],
            [false, false],
        ];
    }

    #[Test, DataProvider('configurationProvider'), AllowMockObjectsWithoutExpectations]
    public function it_sets_the_global_variables(bool $authorsEnabled, bool $tagsEnabled): void
    {
        $this->container->set('authors_enabled', $authorsEnabled);
        $this->container->set('tags_enabled', $tagsEnabled);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())->method('handle')->with(
            $request = $this->createMock(ServerRequestInterface::class)
        );

        $this->container->call(RegisterGlobalsMiddleware::class, [
            'request' => $request,
            'handler' => $handler,
        ]);

        $this->assertEquals([
            'authors_enabled' => $authorsEnabled,
            'tags_enabled' => $tagsEnabled,
        ], $this->view->getEnvironment()->getGlobals());
    }
}
