<?php

declare(strict_types=1);

namespace Tests\Data;

use App\Data\Post;
use App\Data\PostImage;
use Carbon\Carbon;
use League\CommonMark\ConverterInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Post::class)]
class PostTest extends TestCase
{
    #[Test]
    public function it_can_construct_a_post_with_all_parameters_specified(): void
    {
        $post = new Post(
            title: 'Test title; please ignore',
            body: "<excerpt>Hello world!</excerpt>\n<p>This is a test post</p>",
            published: Carbon::parse('1986-05-20 12:34:56'),
            author: 'Arthur Dent',
            tags: ['Foo', 'Bar', 'Baz'],
            image: new PostImage('https://example.com/test.png', 'Test caption; please ignore'),
            draft: true,
        );

        $this->assertSame('Test title; please ignore', $post->title);
        $this->assertSame("<excerpt>Hello world!</excerpt>\n<p>This is a test post</p>", $post->body);
        $this->assertSame('Hello world!', $post->excerpt);
        $this->assertTrue($post->published->equalTo('1986-05-20 12:34:56'));
        $this->assertSame('Arthur Dent', $post->author);
        $this->assertSame(['Foo', 'Bar', 'Baz'], $post->tags);
        $this->assertInstanceOf(PostImage::class, $post->image);
        $this->assertTrue($post->draft);
    }

    #[Test]
    public function it_can_construct_a_post_with_default_parameters(): void
    {
        $post = new Post(
            title: 'Test title; please ignore',
            body: "<h1>Hello world!</h1>\n<p>This is a test post</p>",
            published: Carbon::parse('1986-05-20 12:34:56'),
        );

        $this->assertSame('Test title; please ignore', $post->title);
        $this->assertSame("<h1>Hello world!</h1>\n<p>This is a test post</p>", $post->body);
        $this->assertTrue($post->published->equalTo('1986-05-20 12:34:56'));
        $this->assertNull($post->author);
        $this->assertSame([], $post->tags);
        $this->assertNull($post->image);
        $this->assertFalse($post->draft);
    }

    #[Test]
    public function it_can_construct_a_post_from_rendered_content(): void
    {
        $converter = $this->container->get(ConverterInterface::class);

        $renderedContent = $converter->convert(
            $this->fileContents('posts/draft-post.md')
        );

        $post = Post::fromRenderedContent($renderedContent);

        $this->assertSame('Draft Post; Please Ignore', $post->title);
        $this->assertSame("<p>Draft post; please ignore</p>\n", $post->body);
        $this->assertTrue($post->published->equalTo('1986-05-20 12:34:56'));
        $this->assertSame('Arthur Dent', $post->author);
        $this->assertSame(['Foo', 'Bar', 'Baz'], $post->tags);
        $this->assertNull($post->image);
        $this->assertTrue($post->draft);
    }
}
