<?php

declare(strict_types=1);

namespace Tests;

use App\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use UnexpectedValueException;

#[CoversClass(Config::class)]
class ConfigTest extends TestCase
{
    #[Test]
    public function it_can_retrieve_a_preset_configuration_value(): void
    {
        $this->container->set('test_string', 'Test string; please ignore');

        $string = $this->config->get('test_string');

        $this->assertEquals('Test string; please ignore', $string);
    }

    #[Test]
    public function it_returns_a_default_value(): void
    {
        $default = $this->config->get('defualt_test', 'Test default; please ignore');

        $this->assertEquals('Test default; please ignore', $default);
    }

    #[Test]
    public function it_can_retrieve_a_boolean_value(): void
    {
        $this->container->set('test_true', 'true');
        $this->container->set('test_false', 'false');

        $this->assertTrue($this->config->get('test_true'));
        $this->assertFalse($this->config->get('test_false'));
    }

    #[Test]
    public function it_can_retrieve_a_null_value(): void
    {
        $this->container->set('null_test', 'null');

        $this->assertNull($this->config->get('null_test'));
    }

    #[Test]
    public function it_can_retrieve_an_array_value(): void
    {
        $this->container->set('array_test', ['foo', 'bar', 'baz']);

        $array = $this->config->get('array_test');

        $this->assertEquals(['foo', 'bar', 'baz'], $array);
    }

    #[Test]
    public function it_can_be_surrounded_by_quotation_marks(): void
    {
        $this->container->set('quotes_test', '"Test quotes; please ignore"');

        $item = $this->config->get('quotes_test');

        $this->assertEquals('Test quotes; please ignore', $item);
    }

    #[Test]
    public function it_can_retrieve_a_typed_array_value(): void
    {
        $this->container->set('test_array', ['Foo', 'Bar', 'Baz']);
        $this->container->set('test_string', 'Not an array');

        $this->assertSame(['Foo', 'Bar', 'Baz'], $this->config->array('test_array'));

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Configuration value for key [test_string] must be an array, string given.');

        $this->config->array('test_string');
    }

    #[Test]
    public function it_can_retrieve_a_typed_boolean_value(): void
    {
        $this->container->set('test_boolean', true);
        $this->container->set('test_string', 'false');
        $this->container->set('test_array', ['Foo', 'Bar', 'Baz']);

        $this->assertTrue($this->config->boolean('test_boolean'));
        $this->assertFalse($this->config->boolean('test_string'));

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Configuration value for key [test_array] must be a boolean, array given.');

        $this->config->boolean('test_array');
    }

    #[Test]
    public function it_can_retrieve_a_typed_integer_value(): void
    {
        $this->container->set('test_integer', 1337);
        $this->container->set('test_string', '42');
        $this->container->set('test_array', ['Foo', 'Bar', 'Baz']);

        $this->assertSame(1337, $this->config->integer('test_integer'));
        $this->assertSame(42, $this->config->integer('test_string'));

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Configuration value for key [test_array] must be an integer, array given.');

        $this->config->integer('test_array');
    }

    #[Test]
    public function it_can_retrieve_a_typed_string_value(): void
    {
        $this->container->set('test_string', 'Hello world!');
        $this->container->set('test_integer', 1337);
        $this->container->set('test_array', ['Foo', 'Bar', 'Baz']);

        $this->assertSame('Hello world!', $this->config->string('test_string'));
        $this->assertSame('1337', $this->config->string('test_integer'));

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Configuration value for key [test_array] must be a string, array given.');

        $this->config->string('test_array');
    }
}
