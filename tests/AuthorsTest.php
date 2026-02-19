<?php

declare(strict_types=1);

namespace Tests;

use App\Authors;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(Authors::class)]
class AuthorsTest extends TestCase
{
    private Authors $authors;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authors = $this->container->get(Authors::class);
    }

    #[Test]
    public function it_returns_a_collection_of_authors_with_a_count_of_their_posts(): void
    {
        $authors = $this->authors->withCount();

        $this->assertEquals([
            'Arthur Dent' => 1,
            'Ford Prefect' => 1,
        ], iterator_to_array($authors));
    }

    #[Test]
    public function it_returns_a_couny_of_unique_authors(): void
    {
        $count = $this->authors->count();

        $this->assertSame(2, $count);
    }
}
