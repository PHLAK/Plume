<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Authors;
use DI\Attribute\Inject;
use Illuminate\Support\Collection;
use Symfony\Contracts\Cache\CacheInterface;

class CachedAuthors extends Authors
{
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    public function withCount(): Collection
    {
        return $this->cache->get('authors-with-count', fn (): Collection => parent::withCount());
    }

    public function count(): int
    {
        return $this->cache->get('authors-count', fn (): int => parent::count());
    }
}
