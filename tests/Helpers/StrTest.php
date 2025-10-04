<?php

declare(strict_types=1);

namespace Tests\Helpers;

use App\Helpers\Str;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Str::class)]
class StrTest extends TestCase
{
    #[Test]
    public function it_returns_an_array_of_pattern_matches_from_a_string(): void
    {
        $matches = Str::match('/\[(?<first>[A-Z]+)\].+\((?<second>[0-9]+)\)/', '[FOO] Something (1337)');

        $this->assertSame([
            'first' => 'FOO',
            'second' => '1337',
        ], $matches);
    }

    #[Test]
    public function it_returns_an_empty_array_when_there_are_no_matches(): void
    {
        $matches = Str::match('/\[(?<first>[A-Z]+)\].+/', 'String with no matches');

        $this->assertSame([], $matches);
    }
}
