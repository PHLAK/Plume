<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Commands\PublishPost;
use App\Commands\PublishPosts;
use App\Factories\ConsoleAppFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Application;
use Tests\TestCase;

#[CoversClass(ConsoleAppFactory::class)]
class ConsoleAppFactoryTest extends TestCase
{
    private const array COMMANDS = [
        'publish:post' => PublishPost::class,
        'publish:posts' => PublishPosts::class,
    ];

    #[Test]
    public function it_returns_the_console_application_and_registers_the_commands(): void
    {
        /** @var Application $application */
        $application = $this->container->call(ConsoleAppFactory::class);

        $this->assertInstanceOf(Application::class, $application);

        foreach (self::COMMANDS as $name => $class) {
            $this->assertInstanceOf($class, $application->get($name));
        }
    }
}
