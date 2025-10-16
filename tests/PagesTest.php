<?php

declare(strict_types=1);

namespace Tests;

use App\Data\Page;
use App\Pages;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(Pages::class)]
class PagesTest extends TestCase
{
    private Pages $pages;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pages = $this->container->get(Pages::class);
    }

    #[Test]
    public function it_returns_a_collection_of_all_pages(): void
    {
        $pages = $this->pages->all();

        $this->assertEquals(new Collection([
            'about' => new Page(
                title: 'About this Blog',
                link: 'About',
                weight: 0,
                body: "<p>This is the test about page.</p>\n"
            ),
            'test' => new Page(
                title: 'Test Page; Please Ignore',
                link: 'Test',
                weight: 0,
                body: "<p>I'm a test page, please ignore me.</p>\n"
            ),
            'last' => new Page(
                title: 'Last',
                link: 'Last',
                weight: 999,
                body: "<p>I should apear last in the navigation bar.</p>\n"
            ),
        ]), $pages);
    }

    #[Test]
    public function it_returns_a_page_by_slug(): void
    {
        $page = $this->pages->get('test');

        $this->assertEquals(new Page(
            title: 'Test Page; Please Ignore',
            link: 'Test',
            weight: 0,
            body: "<p>I'm a test page, please ignore me.</p>\n"
        ), $page);
    }
}
