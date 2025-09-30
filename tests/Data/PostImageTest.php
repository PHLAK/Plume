<?php

declare(strict_types=1);

namespace Tests\Data;

use App\Data\PostImage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PostImage::class)]
class PostImageTest extends TestCase
{
    #[Test]
    public function it_can_construct_a_post_image(): void
    {
        $image = new PostImage('https://example.com/image.png', 'Test caption; please ignore');

        $this->assertSame('https://example.com/image.png', $image->url);
        $this->assertSame('Test caption; please ignore', $image->caption);
    }

    #[Test]
    public function it_can_construct_a_post_image_with_default_parameters(): void
    {
        $image = new PostImage('https://example.com/image.png');

        $this->assertSame('https://example.com/image.png', $image->url);
        $this->assertNull($image->caption);
    }
}
