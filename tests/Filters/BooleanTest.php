<?php

declare(strict_types=1);

namespace Tests\Filters;

use App\Filters\Boolean;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Boolean::class)]
class BooleanTest extends TestCase
{
    #[Test]
    public function it_converts_values_to_their_boolean_equivilent(): void
    {
        $booleanTrue = $this->container->call(Boolean::class, ['value' => true]);
        $stringTrue = $this->container->call(Boolean::class, ['value' => 'true']);
        $stringYes = $this->container->call(Boolean::class, ['value' => 'yes']);
        $integerOne = $this->container->call(Boolean::class, ['value' => 'true']);

        $booleanFalse = $this->container->call(Boolean::class, ['value' => false]);
        $stringFalse = $this->container->call(Boolean::class, ['value' => 'false']);
        $stringNo = $this->container->call(Boolean::class, ['value' => 'no']);
        $integerZero = $this->container->call(Boolean::class, ['value' => 'false']);

        $randomString = $this->container->call(Boolean::class, ['value' => 'Some other value...']);
        $randomInteger = $this->container->call(Boolean::class, ['value' => 1337]);
        $array = $this->container->call(Boolean::class, ['value' => ['foo', 'bar', 'baz']]);

        $this->assertTrue($booleanTrue);
        $this->assertTrue($stringYes);
        $this->assertTrue($stringTrue);
        $this->assertTrue($integerOne);

        $this->assertFalse($booleanFalse);
        $this->assertFalse($stringFalse);
        $this->assertFalse($stringNo);
        $this->assertFalse($integerZero);
        $this->assertFalse($randomString);
        $this->assertFalse($randomInteger);
        $this->assertFalse($array);
    }
}
