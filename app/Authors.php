<?php

declare(strict_types=1);

namespace App;

use DI\Attribute\Inject;
use Illuminate\Support\Collection;

class Authors
{
    #[Inject(Posts::class)]
    private Posts $posts;

    /** @return Collection<int, string> */
    public function withCount(): Collection
    {
        return $this->posts->all()->pluck('author')->filter()->flatten()->countBy()->sortKeys();
    }

    public function count(): int
    {
        return $this->posts->all()->pluck('author')->filter()->unique()->count();
    }
}
