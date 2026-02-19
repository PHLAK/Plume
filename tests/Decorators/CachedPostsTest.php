<?php

declare(strict_types=1);

namespace Tests\Decorators;

use App\Data\Post;
use App\Decorators\CachedPosts;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(CachedPosts::class)]
class CachedPostsTest extends TestCase
{
    private CachedPosts $cachedPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cachedPosts = $this->container->make(CachedPosts::class);
    }

    #[Test]
    public function it_caches_a_single_post_by_slug(): void
    {
        $post = $this->cachedPosts->get('test-post-1');

        $expected = new Post(
            title: 'Test Post; Please Ignore',
            published: Carbon::parse('1986-05-20 12:34:56'),
            author: 'Arthur Dent',
            tags: ['Foo', 'Bar', 'Test'],
            body: "<p><excerpt>Lorem ipsum dolor sit amet</excerpt>, consectetur adipiscing elit.</p>\n"
        );

        $this->assertEquals($expected, $post);
        $this->assertEquals($expected, $this->cache->withSubNamespace('posts')->get('test-post-1', function (): void {
            $this->fail('Failed to fetch data from the cache.');
        }));
    }

    #[Test]
    public function it_caches_a_collection_of_all_posts(): void
    {
        $posts = $this->cachedPosts->all();

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

        $this->assertEquals($expected, iterator_to_array($posts));
        $this->assertEquals($expected, $this->cache->get('all-posts', function (): void {
            $this->fail('Failed to fetch data from the cache.');
        }));
    }

    #[Test]
    public function it_caches_a_collection_of_posts_with_a_tag(): void
    {
        $posts = $this->cachedPosts->withTag('Foo');

        $expected = [
            'test-post-1' => new Post(
                title: 'Test Post; Please Ignore',
                published: Carbon::parse('1986-05-20 12:34:56'),
                author: 'Arthur Dent',
                tags: ['Foo', 'Bar', 'Test'],
                body: "<p><excerpt>Lorem ipsum dolor sit amet</excerpt>, consectetur adipiscing elit.</p>\n"
            ),
        ];

        $this->assertEquals($expected, iterator_to_array($posts));
        $this->assertEquals($expected, $this->cache->withSubNamespace('posts-with-tag')->get('Foo', function (): void {
            $this->fail('Failed to fetch data from the cache.');
        }));
    }
}
