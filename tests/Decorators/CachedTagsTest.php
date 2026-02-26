<?php

declare(strict_types=1);

namespace Tests\Decorators;

use App\Decorators\CachedTags;
use App\Posts;
use Closure;
use Generator;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Tests\TestCase;

#[CoversClass(CachedTags::class)]
class CachedTagsTest extends TestCase
{
    private AbstractAdapter&MockObject $tagsCache;
    private CachedTags $cachedTags;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tagsCache = $this->createMock(AbstractAdapter::class);

        $cacheInterface = $this->mock(AbstractAdapter::class, as: CacheInterface::class);
        $cacheInterface->expects($this->once())->method('withSubNamespace')->with(
            $this->identicalTo('tags')
        )->willReturn($this->tagsCache);

        $this->mock(Posts::class);

        $this->cachedTags = $this->container->make(CachedTags::class);
    }

    #[Test]
    public function it_caches_a_collection_of_tags_with_count(): void
    {
        $this->tagsCache->expects($this->once())->method('get')->with(
            $this->identicalTo('with-count'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(
            new LazyCollection(fn (): Generator => yield from ['Bar' => 5, 'Baz' => 2, 'Foo' => 3])
        );

        $tags = $this->cachedTags->withCount();

        $this->assertEquals(['Bar' => 5, 'Baz' => 2, 'Foo' => 3], iterator_to_array($tags));
    }
}
