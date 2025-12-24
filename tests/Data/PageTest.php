<?php

declare(strict_types=1);

namespace Tests\Data;

use App\Data\Page;
use League\CommonMark\ConverterInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Page::class)]
class PageTest extends TestCase
{
    #[Test]
    public function it_can_construct_a_page_with_all_parameters(): void
    {
        $page = new Page(
            title: 'Test title; please ignore',
            link: 'Test',
            body: '<p>This is a test post.</p>',
            weight: 10,
        );

        $this->assertSame('Test title; please ignore', $page->title);
        $this->assertSame('Test', $page->link);
        $this->assertSame('<p>This is a test post.</p>', $page->body);
        $this->assertSame(10, $page->weight);
    }

    #[Test]
    public function it_can_construct_a_page_from_rendered_content(): void
    {
        $converter = $this->container->get(ConverterInterface::class);

        $renderedContent = $converter->convert(
            $this->fileContents('data/pages/test.md')
        );

        $page = Page::fromRenderedContent($renderedContent);

        $this->assertSame('Test Page; Please Ignore', $page->title);
        $this->assertSame('Test', $page->link);
        $this->assertSame("<p>I'm a test page, please ignore me.</p>\n", $page->body);
        $this->assertSame(0, $page->weight);
    }
}
