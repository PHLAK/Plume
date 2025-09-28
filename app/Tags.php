<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Collection;

class Tags // TODO: Cache this with a decorator
{
    public function __construct(
        private Posts $posts
    ) {}

    public function withCount(): Collection
    {
        return $this->posts->all()->pluck('tags')->flatten()->countBy()->sortKeys();
    }
}
