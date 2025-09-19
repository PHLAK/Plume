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
        int $perPage,
        int $currentPage,
    ) {
        $this->pages = (int) ceil(count($items) / $perPage);
        $this->previous = ($previous = $currentPage - 1) >= 1 ? $previous : null;
        $this->next = ($next = $currentPage + 1) <= $this->pages ? $next : null;
    }
}
