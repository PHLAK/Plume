<?php

declare(strict_types=1);

namespace Tests\Decorators;

use App\Data\Page;
use App\Decorators\CachedPages;
use Closure;
use Generator;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Tests\TestCase;

#[CoversClass(CachedPages::class)]
class CachedPagesTest extends TestCase
{
    private AbstractAdapter&MockObject $cacheInterface;
    private CachedPages $cachedPages;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheInterface = $this->mock(AbstractAdapter::class, as: CacheInterface::class);
        $this->cachedPages = $this->container->make(CachedPages::class);
    }

    #[Test]
    public function it_can_get_a_collection_of_pages_and_cache_the_results(): void
    {
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

        $this->cacheInterface->expects($this->once())->method('get')->with(
            $this->identicalTo('all-pages'),
            $this->isInstanceOf(Closure::class)
        )->willReturn(
            new LazyCollection(fn (): Generator => yield from $expected)
        );

        $pages = $this->cachedPages->all();

        $this->assertEquals($expected, iterator_to_array($pages));
    }

    #[Test]
    public function it_can_get_a_page_and_cache_the_result(): void
    {
        $expected = new Page(
            title: 'Test Page; Please Ignore',
            link: 'Test',
            body: "<p>I'm a test page, please ignore me.</p>\n",
            weight: 0,
        );

        $this->cacheInterface->expects($this->once())->method('withSubNamespace')->with(
            $this->identicalTo('pages')
        )->willReturnSelf();

        $this->cacheInterface->expects($this->once())->method('get')->with(
            $this->identicalTo('test'),
            $this->isInstanceOf(Closure::class)
        )->willReturn($expected);

        $page = $this->cachedPages->get('test');

        $this->assertEquals($expected, $page);
    }
}
