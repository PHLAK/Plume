<?php

declare(strict_types=1);

namespace Tests\Decorators;

use App\Decorators\CachedPages;
use League\CommonMark\ConverterInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(CachedPages::class)]
class CachedPagesTest extends TestCase
{
    private CachedPages $cachedPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cachedPosts = new CachedPages($this->config, $this->container->get(ConverterInterface::class), $this->cache);
    }

    #[Test]
    public function it_can_do_something(): void
    {
        // ...

        $this->markTestIncomplete();
    }
}
