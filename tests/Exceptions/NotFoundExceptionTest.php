<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use App\Exceptions\NotFoundException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Tests\TestCase;

#[CoversClass(NotFoundException::class)]
class NotFoundExceptionTest extends TestCase
{
    #[Test]
    public function it_is_a_runtime_exception(): void
    {
        $exception = new NotFoundException;

        $this->assertInstanceOf(RuntimeException::class, $exception);
    }
}
