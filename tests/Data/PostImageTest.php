<?php

declare(strict_types=1);

namespace Tests\Data;

use App\Data\PostImage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PostImage::class)]
class PostImageTest extends TestCase
{
    /** @return array<string, array{string, ?string, string}> */
    public static function urlTestProvider(): array
    {
        return [
            'No base URL' => ['/images/test.png', null, '/images/test.png'],
            'Relative URL' => ['/images/test.png', 'https://example.com', 'https://example.com/images/test.png'],
            'Relative URL without leading slash' => ['images/test.png', 'https://example.com', 'https://example.com/images/test.png'],
            'Base URL with trailing slash' => ['/images/test.png', 'https://example.com/', 'https://example.com/images/test.png'],
            'Absolue URL' => ['https://example.com/test.png', 'https://cdn.example.com', 'https://example.com/test.png'],
            'Protocol-relative URL' => ['//cdn.example.com/test.png', 'https://example.com', '//cdn.example.com/test.png'],
        ];
    }

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

    #[Test, DataProvider('urlTestProvider')]
    public function it_makes_the_url_absolute(string $url, ?string $baseUrl, string $expected): void
    {
        $image = new PostImage($url, 'Test caption; please ignore');

        $absoluteImage = $baseUrl ? $image->forBaseUrl($baseUrl) : $image;

        $this->assertSame($expected, $absoluteImage->url);
        $this->assertSame('Test caption; please ignore', $absoluteImage->caption);
    }
}
