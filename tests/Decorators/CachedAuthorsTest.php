<?php

declare(strict_types=1);

namespace Tests\Decorators;

use App\Decorators\CachedAuthors;
use Closure;
use Generator;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\Cache\CacheInterface;
use Tests\TestCase;

#[CoversClass(CachedAuthors::class)]
class CachedAuthorsTest extends TestCase
{
    private CacheInterface&MockObject $cacheInterface;
    private CachedAuthors $cachedAuthors;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheInterface = $this->mock(CacheInterface::class);
        $this->cachedAuthors = $this->container->make(CachedAuthors::class);
    }

    #[Test]
    public function it_caches_a_collection_of_authors_with_count(): void
    {
        $this->cacheInterface->expects($this->once())->method('get')->with(
            $this->identicalTo('authors-with-count'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(
            new LazyCollection(fn (): Generator => yield from ['Arthur Dent' => 3, 'Ford Prefect' => 2, 'Trisha McMillan' => 1])
        );

        $authors = $this->cachedAuthors->withCount();

        $this->assertEquals(['Arthur Dent' => 3, 'Ford Prefect' => 2, 'Trisha McMillan' => 1], iterator_to_array($authors));
    }

    #[Test]
    public function it_caches_a_count_of_unique_authors(): void
    {
        $this->cacheInterface->expects($this->once())->method('get')->with(
            $this->identicalTo('authors-count'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(3);

        $count = $this->cachedAuthors->count();

        $this->assertEquals(3, $count);
    }
}
