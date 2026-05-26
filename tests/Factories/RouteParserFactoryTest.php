<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Factories\RouteParserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\App;
use Slim\Interfaces\RouteParserInterface;
use Tests\TestCase;

#[CoversClass(RouteParserFactory::class)]
class RouteParserFactoryTest extends TestCase
{
    #[Test]
    public function it_returns_the_route_parser(): void
    {
        $routeParser = $this->container->call(RouteParserFactory::class, [
            'app' => $this->container->get(App::class),
        ]);

        $this->assertInstanceOf(RouteParserInterface::class, $routeParser);
    }
}
