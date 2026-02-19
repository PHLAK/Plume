<?php

declare(strict_types=1);

namespace App;

use DI\Attribute\Inject;
use Illuminate\Support\LazyCollection;

class Tags
{
    #[Inject(Posts::class)]
    private Posts $posts;

    /** @return LazyCollection<int, string> */
    public function withCount(): LazyCollection
    {
        return $this->posts->all()->pluck('tags')->flatten()->countBy()->sortKeys();
    }

    public function count(): int
    {
        return $this->posts->all()->pluck('tags')->flatten()->unique()->count();
    }
}
