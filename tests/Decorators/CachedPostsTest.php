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
    private AbstractAdapter&MockObject $postsCache;
    private CachedPosts $cachedPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->postsCache = $this->createMock(AbstractAdapter::class);

        $cacheInterface = $this->mock(AbstractAdapter::class, as: CacheInterface::class);
        $cacheInterface->expects($this->once())->method('withSubNamespace')->with(
            $this->identicalTo('posts')
        )->willReturn($this->postsCache);

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

        $this->postsCache->expects($this->once())->method('get')->with(
            $this->identicalTo('9e9c650fbf52bb28c474bcf1e0bb54e6'),
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

        $this->postsCache->expects($this->once())->method('get')->with(
            $this->identicalTo('all'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(
            new LazyCollection(fn (): Generator => yield from $expected)
        );

        $posts = $this->cachedPosts->all();

        $this->assertEquals($expected, iterator_to_array($posts));
    }

    #[Test]
    public function it_caches_a_collection_of_posts_by_author(): void
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

        $byAuthorCache = $this->createMock(AbstractAdapter::class);

        $this->postsCache->expects($this->once())->method('withSubNamespace')->with(
            $this->identicalTo('by-author')
        )->willReturn($byAuthorCache);

        $byAuthorCache->expects($this->once())->method('get')->with(
            $this->identicalTo('Arthur Dent'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(
            new LazyCollection(fn (): Generator => yield from $expected)
        );

        $posts = $this->cachedPosts->byAuthor('Arthur Dent');

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

        $withTagCache = $this->createMock(AbstractAdapter::class);

        $this->postsCache->expects($this->once())->method('withSubNamespace')->with(
            $this->identicalTo('with-tag')
        )->willReturn($withTagCache);

        $withTagCache->expects($this->once())->method('get')->with(
            $this->identicalTo('Foo'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(
            new LazyCollection(fn (): Generator => yield from $expected)
        );

        $posts = $this->cachedPosts->withTag('Foo');

        $this->assertEquals($expected, iterator_to_array($posts));
    }
}
