<?php

declare(strict_types=1);

namespace Tests\ViewFunctions;

use App\ViewFunctions\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Config::class)]
class ConfigTest extends TestCase
{
    #[Test]
    public function it_can_retrieve_a_config_item(): void
    {
        $this->container->set('foo', 'Test value; please ignore');

        $value = $this->container->call(Config::class, ['key' => 'foo']);

        $this->assertEquals('Test value; please ignore', $value);
    }

    #[Test]
    public function it_returns_a_default_value(): void
    {
        $value = $this->container->call(Config::class, ['key' => 'bar', 'default' => 'Default value']);

        $this->assertEquals('Default value', $value);
    }
}
