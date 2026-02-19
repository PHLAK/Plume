<?php

declare(strict_types=1);

namespace Tests\Decorators;

use App\Decorators\CachedTags;
use Closure;
use Generator;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\Cache\CacheInterface;
use Tests\TestCase;

#[CoversClass(CachedTags::class)]
class CachedTagsTest extends TestCase
{
    private CacheInterface&MockObject $cacheInterface;
    private CachedTags $cachedTags;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheInterface = $this->mock(CacheInterface::class);
        $this->cachedTags = $this->container->make(CachedTags::class);
    }

    #[Test]
    public function it_caches_a_collection_of_tags_with_count(): void
    {
        $this->cacheInterface->expects($this->once())->method('get')->with(
            $this->identicalTo('tags-with-count'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(
            new LazyCollection(fn (): Generator => yield from ['Bar' => 5, 'Baz' => 2, 'Foo' => 3])
        );

        $tags = $this->cachedTags->withCount();

        $this->assertEquals(['Bar' => 5, 'Baz' => 2, 'Foo' => 3], iterator_to_array($tags));
    }

    #[Test]
    public function it_caches_a_count_of_unique_tags(): void
    {
        $this->cacheInterface->expects($this->once())->method('get')->with(
            $this->identicalTo('tags-count'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(3);

        $count = $this->cachedTags->count();

        $this->assertEquals(3, $count);
    }
}
