<?php

declare(strict_types=1);

namespace Tests\Data;

use App\Data\Paginator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Paginator::class)]
class PaginatorTest extends TestCase
{
    private const array ITEMS = ['Alpha', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India', 'Juliet'];

    public static function paginatorProvider(): array
    {
        return [
            // 2 items per page
            [2, 1, 5, 2, null],
            [2, 2, 5, 3, 1],
            [2, 3, 5, 4, 2],
            [2, 4, 5, 5, 3],
            [2, 5, 5, null, 4],

            // 3 items per page
            [3, 1, 4, 2, null],
            [3, 2, 4, 3, 1],
            [3, 3, 4, 4, 2],
            [3, 4, 4, null, 3],

            // 5 items per page
            [5, 1, 2, 2, null],
            [5, 2, 2, null, 1],

            // 10 itmes per page
            [10, 1, 1, null, null],
        ];
    }

    #[Test, DataProvider('paginatorProvider')]
    public function it_can_contruct_a_paginator(int $perPage, int $currentPage, int $pages, ?int $next, ?int $previous): void
    {
        $paginator = new Paginator(self::ITEMS, $perPage, $currentPage);

        $this->assertSame($pages, $paginator->pages);
        $this->assertSame($next, $paginator->next);
        $this->assertSame($previous, $paginator->previous);
    }
}
