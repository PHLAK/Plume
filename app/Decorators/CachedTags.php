<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Tags;
use DI\Attribute\Inject;
use Illuminate\Support\LazyCollection;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class CachedTags extends Tags
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    public function withCount(): LazyCollection
    {
        return $this->cache->get('tags-with-count', fn (): LazyCollection => parent::withCount());
    }

    public function count(): int
    {
        return $this->cache->get('tags-count', fn (): int => parent::count());
    }
}
