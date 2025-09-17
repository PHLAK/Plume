<?php

declare(strict_types=1);

namespace App\Data;

final readonly class Paginator
{
    public int $pages;
    public ?int $previous;
    public ?int $next;

    public function __construct(
        iterable $items,
        public int $perPage,
        public int $currentPage,
    ) {
        $this->pages = $this->pages($items);
        $this->previous = ($previous = $currentPage - 1) >= 1 ? $previous : null;
        $this->next = ($next = $currentPage + 1) <= $this->pages ? $next : null;
    }

    private function pages(iterable $items): int
    {
        return (int) ceil(count($items) / $this->perPage);
    }
}
