<?php

declare(strict_types=1);

namespace Tests\Decorators;

use App\Data\Post;
use App\Decorators\CachedAuthors;
use App\Posts;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

#[CoversClass(CachedAuthors::class)]
class CachedAuthorsTest extends TestCase
{
    private Posts&MockObject $posts;

    private CachedAuthors $cachedAuthors;

    protected function setUp(): void
    {
        parent::setUp();

        $this->posts = $this->mock(Posts::class);
        $this->cachedAuthors = $this->container->make(CachedAuthors::class);
    }

    #[Test]
    public function it_caches_a_collection_of_authors_with_count(): void
    {
        $this->posts->expects($this->once())->method('all')->willReturn(
            new Collection([
                new Post('Title', 'body', Carbon::now(), author: 'Arthur Dent'),
                new Post('Title', 'body', Carbon::now(), author: 'Ford Prefect'),
                new Post('Title', 'body', Carbon::now(), author: 'Arthur Dent'),
                new Post('Title', 'body', Carbon::now(), author: 'Arthur Dent'),
                new Post('Title', 'body', Carbon::now(), author: 'Trisha McMillan'),
                new Post('Title', 'body', Carbon::now(), author: 'Ford Prefect'),
            ])
        );

        $authors = $this->cachedAuthors->withCount();

        $this->assertEquals(new Collection(['Arthur Dent' => 3, 'Ford Prefect' => 2, 'Trisha McMillan' => 1]), $authors);
        $this->assertEquals(new Collection(['Arthur Dent' => 3, 'Ford Prefect' => 2, 'Trisha McMillan' => 1]), $this->cache->get('authors-with-count', function (): void {
            $this->fail('Failed to fetch data from the cache.');
        }));
    }

    #[Test]
    public function it_caches_a_count_of_unique_authors(): void
    {
        $this->posts->expects($this->once())->method('all')->willReturn(
            new Collection([
                new Post('Title', 'body', Carbon::now(), author: 'Arthur Dent'),
                new Post('Title', 'body', Carbon::now(), author: 'Ford Prefect'),
                new Post('Title', 'body', Carbon::now(), author: 'Arthur Dent'),
                new Post('Title', 'body', Carbon::now(), author: 'Arthur Dent'),
                new Post('Title', 'body', Carbon::now(), author: 'Trisha McMillan'),
            ])
        );

        $count = $this->cachedAuthors->count();

        $this->assertEquals(3, $count);
        $this->assertEquals(3, $this->cache->get('authors-count', function (): void {
            $this->fail('Failed to fetch data from the cache.');
        }));
    }
}
