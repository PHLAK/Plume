<?php

declare(strict_types=1);

namespace Tests\Decorators;

use App\Decorators\CachedAuthors;
use App\Posts;
use Closure;
use Generator;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Tests\TestCase;

#[CoversClass(CachedAuthors::class)]
class CachedAuthorsTest extends TestCase
{
    private AbstractAdapter&MockObject $authorsCache;
    private CachedAuthors $cachedAuthors;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorsCache = $this->createMock(AbstractAdapter::class);

        $cache = $this->mock(AbstractAdapter::class, as: CacheInterface::class);
        $cache->expects($this->once())->method('withSubNamespace')->with(
            $this->identicalTo('authors')
        )->willReturn($this->authorsCache);

        $this->mock(Posts::class);

        $this->cachedAuthors = $this->container->make(CachedAuthors::class);
    }

    #[Test, AllowMockObjectsWithoutExpectations]
    public function it_caches_a_collection_of_authors_with_count(): void
    {
        $this->authorsCache->expects($this->once())->method('get')->with(
            $this->identicalTo('with-count'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(
            new LazyCollection(fn (): Generator => yield from ['Arthur Dent' => 3, 'Ford Prefect' => 2, 'Trisha McMillan' => 1])
        );

        $authors = $this->cachedAuthors->withCount();

        $this->assertEquals(['Arthur Dent' => 3, 'Ford Prefect' => 2, 'Trisha McMillan' => 1], iterator_to_array($authors));
    }
}
