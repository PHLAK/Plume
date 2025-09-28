<?php

declare(strict_types=1);

namespace Tests\Managers;

use App\Middlewares;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MiddlewareManagerTest extends TestCase
{
    /** @const Array of application middlewares */
    private const MIDDLEWARES = [
        // Middlewares\WhoopsMiddleware::class,
        // Middlewares\PruneCacheMiddleware::class,
        // Middlewares\CacheControlMiddleware::class,
        // Middlewares\RegisterGlobalsMiddleware::class,
    ];

    #[Test]
    public function it_registers_application_middlewares(): void
    {
        // ...

        $this->markTestIncomplete();
    }
}
