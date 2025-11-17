<?php

declare(strict_types=1);

namespace App\Utilities;

use DI\Attribute\Inject;

class Paginator
{
    #[Inject('posts_per_page')]
    private int $postsPerPage;

    public function __construct(
        public int $pages = 1,
        public ?int $previous = null,
        public ?int $next = null,
    ) {}

    public function of(iterable $items): self
    {
        $this->pages = (int) ceil(count($items) / $this->postsPerPage);

        return $this;
    }

    public function page(int $page): self
    {
        $this->previous = ($previous = $page - 1) >= 1 ? $previous : null;
        $this->next = ($next = $page + 1) <= $this->pages ? $next : null;

        return $this;
    }
}
