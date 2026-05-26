<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Factories\SearchFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use YetiSearch\YetiSearch;

#[CoversClass(SearchFactory::class)]
class SearchFactoryTest extends TestCase
{
    #[Test]
    public function it_returns_a_yetisearch_instance(): void
    {
        $search = $this->container->call(SearchFactory::class);

        $this->assertInstanceOf(YetiSearch::class, $search);
    }
}
