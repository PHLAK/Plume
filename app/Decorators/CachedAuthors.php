<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Authors;
use DI\Attribute\Inject;
use Illuminate\Support\LazyCollection;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class CachedAuthors extends Authors
{
    /** @var AbstractAdapter */
    private CacheInterface $cache;

    /** @param AbstractAdapter $cache */
    public function __construct(
        #[Inject(CacheInterface::class)] CacheInterface $cache,
    ) {
        $this->cache = $cache->withSubNamespace('authors');
    }

    public function withCount(): LazyCollection
    {
        return $this->cache->get('with-count', fn (): LazyCollection => parent::withCount());
    }
}
