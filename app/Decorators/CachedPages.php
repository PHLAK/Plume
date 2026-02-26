<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Data\Page;
use App\Pages;
use DI\Attribute\Inject;
use Illuminate\Support\LazyCollection;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class CachedPages extends Pages
{
    /** @var AbstractAdapter */
    private CacheInterface $cache;

    /** @param AbstractAdapter $cache */
    public function __construct(
        #[Inject(CacheInterface::class)] CacheInterface $cache,
    ) {
        $this->cache = $cache->withSubNamespace('pages');
    }

    public function all(): LazyCollection
    {
        return $this->cache->get('all', fn (): LazyCollection => parent::all());
    }

    public function get(string $slug): Page
    {
        return $this->cache->get(hash('xxh128', $slug), fn (): Page => parent::get($slug));
    }
}
