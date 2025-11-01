<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Data\Page;
use App\Pages;
use DI\Attribute\Inject;
use Illuminate\Support\Collection;
use Symfony\Contracts\Cache\CacheInterface;

class CachedPages extends Pages
{
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    public function all(): Collection
    {
        return $this->cache->get('all-pages', fn (): Collection => parent::all());
    }

    public function get(string $slug): Page
    {
        return $this->cache->get(sprintf('page|%s', $slug), fn (): Page => parent::get($slug));
    }
}
