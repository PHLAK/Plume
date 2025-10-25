<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Collection;

class Authors
{
    public function __construct(
        private Posts $posts
    ) {}

    public function withCount(): Collection
    {
        return $this->posts->all()->pluck('author')->filter()->flatten()->countBy()->sortKeys();
    }
}
