<?php

declare(strict_types=1);

namespace App;

use DI\Attribute\Inject;
use Illuminate\Support\LazyCollection;

class Authors
{
    #[Inject(Posts::class)]
    private Posts $posts;

    /** @return LazyCollection<string, int> */
    public function withCount(): LazyCollection
    {
        return $this->posts->all()->pluck('author')->filter()->flatten()->countBy()->sortKeys();
    }

    public function count(): int
    {
        return $this->posts->all()->pluck('author')->filter()->unique()->count();
    }
}
