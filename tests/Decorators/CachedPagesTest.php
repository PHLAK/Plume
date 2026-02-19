<?php

declare(strict_types=1);

namespace Tests\Decorators;

use App\Data\Page;
use App\Decorators\CachedPages;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(CachedPages::class)]
class CachedPagesTest extends TestCase
{
    private CachedPages $cachedPages;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cachedPages = $this->container->make(CachedPages::class);
    }

    #[Test]
    public function it_can_get_a_collection_of_pages_and_cache_the_results(): void
    {
        $pages = $this->cachedPages->all();

        $expected = [
            'about' => new Page(
                title: 'About this Blog',
                link: 'About',
                body: "<p>This is the test about page.</p>\n",
                weight: 0,
            ),
            'test' => new Page(
                title: 'Test Page; Please Ignore',
                link: 'Test',
                body: "<p>I'm a test page, please ignore me.</p>\n",
                weight: 0,
            ),
            'last' => new Page(
                title: 'Last',
                link: 'Last',
                body: "<p>I should apear last in the navigation bar.</p>\n",
                weight: 999,
            ),
        ];

        $this->assertEquals($expected, iterator_to_array($pages));
        $this->assertEquals($expected, $this->cache->get('all-pages', function (): void {
            $this->fail('Failed to fetch data from the cache.');
        }));
    }

    #[Test]
    public function it_can_get_a_page_and_cache_the_result(): void
    {
        $page = $this->cachedPages->get('test');

        $expected = new Page(
            title: 'Test Page; Please Ignore',
            link: 'Test',
            body: "<p>I'm a test page, please ignore me.</p>\n",
            weight: 0,
        );

        $this->assertEquals($expected, $page);
        $this->assertEquals($expected, $this->cache->withSubNamespace('pages')->get('test', function (): void {
            $this->fail('Failed to fetch data from the cache.');
        }));
    }
}
