<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Factories\TwigFactory;
use Invoker\CallableResolver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\Views\Twig;
use Tests\TestCase;

#[CoversClass(TwigFactory::class)]
class TwigFactoryTest extends TestCase
{
    #[Test]
    public function it_returns_a_configured_twig_object(): void
    {
        // $callableResolver = $this->container->get(CallableResolver::class);

        // $twig = (new TwigFactory($this->config, $callableResolver))();

        $twig = $this->container->call(TwigFactory::class);

        $this->assertInstanceOf(Twig::class, $twig);
    }
}
