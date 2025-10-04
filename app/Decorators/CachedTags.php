<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Posts;
use App\Tags;
use Illuminate\Support\Collection;
use Symfony\Contracts\Cache\CacheInterface;

class CachedTags extends Tags
{
    public function __construct(
        private Posts $posts,
        private CacheInterface $cache,
    ) {
        parent::__construct($posts);
    }

    public function withCount(): Collection
    {
        return $this->cache->get('tags|with-count', fn (): Collection => parent::withCount());
    }
}
