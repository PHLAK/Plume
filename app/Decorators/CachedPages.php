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
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    public function all(): LazyCollection
    {
        return $this->cache->get('all-pages', fn (): LazyCollection => parent::all());
    }

    public function get(string $slug): Page
    {
        return $this->cache->withSubNamespace('pages')->get($slug, fn (): Page => parent::get($slug));
    }
}
