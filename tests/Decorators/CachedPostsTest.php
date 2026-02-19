<?php

declare(strict_types=1);

namespace Tests\Decorators;

use App\Data\Post;
use App\Decorators\CachedPosts;
use Carbon\Carbon;
use Closure;
use Generator;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Tests\TestCase;

#[CoversClass(CachedPosts::class)]
class CachedPostsTest extends TestCase
{
    private AbstractAdapter&MockObject $cacheInterface;
    private CachedPosts $cachedPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheInterface = $this->mock(AbstractAdapter::class, as: CacheInterface::class);
        $this->cachedPosts = $this->container->make(CachedPosts::class);
    }

    #[Test]
    public function it_caches_a_single_post_by_slug(): void
    {
        $expected = new Post(
            title: 'Test Post; Please Ignore',
            published: Carbon::parse('1986-05-20 12:34:56'),
            author: 'Arthur Dent',
            tags: ['Foo', 'Bar', 'Test'],
            body: "<p><excerpt>Lorem ipsum dolor sit amet</excerpt>, consectetur adipiscing elit.</p>\n"
        );

        $this->cacheInterface->expects($this->once())->method('withSubNamespace')->with(
            $this->identicalTo('posts')
        )->willReturnSelf();

        $this->cacheInterface->expects($this->once())->method('get')->with(
            $this->identicalTo('test-post-1'),
            $this->isInstanceOf(Closure::class)
        )->willReturn($expected);

        $post = $this->cachedPosts->get('test-post-1');

        $this->assertEquals($expected, $post);
    }

    #[Test]
    public function it_caches_a_collection_of_all_posts(): void
    {
        $expected = [
            'test-post-2' => new Post(
                title: 'Another Test Post',
                published: Carbon::parse('1986-07-06 12:34:56'),
                author: 'Ford Prefect',
                tags: ['Test', 'Baz'],
                body: "<p>I'm a post with tags!</p>\n"
            ),
            'test-post-1' => new Post(
                title: 'Test Post; Please Ignore',
                published: Carbon::parse('1986-05-20 12:34:56'),
                author: 'Arthur Dent',
                tags: ['Foo', 'Bar', 'Test'],
                body: "<p><excerpt>Lorem ipsum dolor sit amet</excerpt>, consectetur adipiscing elit.</p>\n"
            ),
        ];

        $this->cacheInterface->expects($this->once())->method('get')->with(
            $this->identicalTo('all-posts'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(
            new LazyCollection(fn (): Generator => yield from $expected)
        );

        $posts = $this->cachedPosts->all();

        $this->assertEquals($expected, iterator_to_array($posts));
    }

    #[Test]
    public function it_caches_a_collection_of_posts_with_a_tag(): void
    {
        $expected = [
            'test-post-1' => new Post(
                title: 'Test Post; Please Ignore',
                published: Carbon::parse('1986-05-20 12:34:56'),
                author: 'Arthur Dent',
                tags: ['Foo', 'Bar', 'Test'],
                body: "<p><excerpt>Lorem ipsum dolor sit amet</excerpt>, consectetur adipiscing elit.</p>\n"
            ),
        ];

        $this->cacheInterface->expects($this->once())->method('withSubNamespace')->with(
            $this->identicalTo('posts-with-tag')
        )->willReturnSelf();

        $this->cacheInterface->expects($this->once())->method('get')->with(
            $this->identicalTo('Foo'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(
            new LazyCollection(fn (): Generator => yield from $expected)
        );

        $posts = $this->cachedPosts->withTag('Foo');

        $this->assertEquals($expected, iterator_to_array($posts));
    }
}
