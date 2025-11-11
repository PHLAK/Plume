<?php

declare(strict_types=1);

namespace Tests;

use App\Tags;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(Tags::class)]
class TagsTest extends TestCase
{
    private Tags $tags;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tags = $this->container->get(Tags::class);
    }

    #[Test]
    public function it_returns_a_collection_of_tags_with_count(): void
    {
        $tags = $this->tags->withCount();

        $this->assertSame([
            'Bar' => 1,
            'Baz' => 1,
            'Foo' => 1,
            'Test' => 2,
        ], $tags->toArray());
    }

    #[Test]
    public function it_returns_a_count_of_unique_tags(): void
    {
        $count = $this->tags->count();

        $this->assertSame(4, $count);
    }
}
