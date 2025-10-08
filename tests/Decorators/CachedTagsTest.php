<?php

declare(strict_types=1);

namespace Tests\Decorators;

use App\Data\Post;
use App\Decorators\CachedTags;
use App\Posts;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

#[CoversClass(CachedTags::class)]
class CachedTagsTest extends TestCase
{
    private Posts&MockObject $posts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->posts = $this->createMock(Posts::class);
    }

    #[Test]
    public function it_caches_a_collection_of_tags_with_count(): void
    {
        $this->posts->expects($this->once())->method('all')->willReturn(
            new Collection([
                new Post('Title', 'body', Carbon::now(), tags: ['Foo', 'Bar', 'Baz']),
                new Post('Title', 'body', Carbon::now(), tags: ['Foo', 'Bar']),
                new Post('Title', 'body', Carbon::now(), tags: ['Bar', 'Baz']),
                new Post('Title', 'body', Carbon::now(), tags: ['Foo', 'Bar']),
                new Post('Title', 'body', Carbon::now(), tags: ['Bar']),
            ])
        );

        $tags = new CachedTags($this->posts, $this->cache)->withCount();

        $this->assertEquals(new Collection(['Bar' => 5, 'Baz' => 2, 'Foo' => 3]), $tags);
        $this->assertEquals(new Collection(['Bar' => 5, 'Baz' => 2, 'Foo' => 3]), $this->cache->get('tags|with-count', fn () => null));
    }
}
