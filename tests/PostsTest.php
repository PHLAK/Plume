<?php

declare(strict_types=1);

namespace Tests;

use App\Data\Post;
use App\Posts;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(Posts::class)]
class PostsTest extends TestCase
{
    private Posts $posts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->posts = $this->container->get(Posts::class);
    }

    #[Test]
    public function it_returns_a_collection_of_all_posts_excluding_drafts_and_future_posts(): void
    {
        $posts = $this->posts->all();

        $this->assertCount(2, $posts);
        $this->assertContainsOnlyInstancesOf(Post::class, $posts);
        $this->assertTrue($posts->doesntContain(fn (Post $post): bool => $post->draft || $post->published->isFuture()));

        /** @var Post $testPost */
        $testPost = $posts->get('test-post-1');

        $this->assertSame('Test Post; Please Ignore', $testPost->title);
        $this->assertSame('Lorem ipsum dolor sit amet', $testPost->excerpt);
        $this->assertTrue($testPost->published->equalTo('1986-05-20 12:34:56'));
        $this->assertSame('Arthur Dent', $testPost->author);
        $this->assertSame(['Foo', 'Bar', 'Test'], $testPost->tags);
        $this->assertNull($testPost->image);
        $this->assertFalse($testPost->draft);
        $this->assertSame(<<<HTML
        <p><excerpt>Lorem ipsum dolor sit amet</excerpt>, consectetur adipiscing elit.
        Donec ullamcorper posuere quam ac varius. Pellentesque habitant morbi tristique
        senectus et netus et malesuada fames ac turpis egestas. Fusce maximus enim a
        elementum blandit.</p>

        HTML, $testPost->body);
    }

    #[Test]
    public function it_returns_a_collection_posts_with_a_tag(): void
    {
        $posts = $this->posts->withTag('Test');

        $this->assertCount(2, $posts);
        $this->assertContainsOnlyInstancesOf(Post::class, $posts);
        $this->assertTrue($posts->doesntContain(fn (Post $post): bool => $post->draft || $post->published->isFuture()));

        $this->assertTrue($posts->every(fn (Post $post): bool => in_array('Test', $post->tags)));
    }
}
