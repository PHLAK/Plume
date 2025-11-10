<?php

declare(strict_types=1);

namespace App;

use DI\Attribute\Inject;
use Illuminate\Support\Collection;

class Tags
{
    #[Inject(Posts::class)]
    private Posts $posts;

    public function withCount(): Collection
    {
        return $this->posts->all()->pluck('tags')->flatten()->countBy()->sortKeys();
    }

    public function count(): int
    {
        return $this->posts->all()->pluck('tags')->flatten()->unique()->count();
    }
}
