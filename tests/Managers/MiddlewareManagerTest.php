<?php

declare(strict_types=1);

namespace Tests\Managers;

use App\Managers\MiddlewareManager;
use App\Middlewares;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\App;
use Tests\TestCase;

#[CoversClass(MiddlewareManager::class)]
class MiddlewareManagerTest extends TestCase
{
    #[Test]
    public function it_registers_application_middlewares(): void
    {
        $this->container->set('middlewares', $middlewares = [
            Middlewares\WhoopsMiddleware::class,
            fn (): string => 'test-middleware-please-ignore',
        ]);

        $app = $this->mock(App::class);
        $app->expects($matcher = $this->atLeast(1))->method('add')->willReturnCallback(
            function ($parameter) use ($matcher, $app, $middlewares): App {
                $this->assertSame($middlewares[$matcher->numberOfInvocations() - 1], $parameter);

                return $app;
            }
        );

        $this->container->call(MiddlewareManager::class);
    }
}
