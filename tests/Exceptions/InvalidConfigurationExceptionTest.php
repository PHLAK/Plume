<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(InvalidConfigurationException::class)]
class InvalidConfigurationExceptionTest extends TestCase
{
    #[Test]
    public function it_can_do_something(): void
    {
        // ...

        $this->markTestIncomplete();
    }
}
