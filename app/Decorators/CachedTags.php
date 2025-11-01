<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Tags;
use DI\Attribute\Inject;
use Illuminate\Support\Collection;
use Symfony\Contracts\Cache\CacheInterface;

class CachedTags extends Tags
{
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    public function withCount(): Collection
    {
        return $this->cache->get('tags|with-count', fn (): Collection => parent::withCount());
    }
}
