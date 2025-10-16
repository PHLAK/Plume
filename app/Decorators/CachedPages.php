<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Config;
use App\Data\Page;
use App\Pages;
use Illuminate\Support\Collection;
use League\CommonMark\ConverterInterface;
use Symfony\Contracts\Cache\CacheInterface;

class CachedPages extends Pages
{
    public function __construct(
        private Config $config,
        private ConverterInterface $converter,
        private CacheInterface $cache,
    ) {
        parent::__construct($config, $converter);
    }

    public function all(): Collection
    {
        return $this->cache->get('all-pages', fn (): Collection => parent::all());
    }

    public function get(string $slug): Page
    {
        return $this->cache->get(sprintf('page|%s', $slug), fn (): Page => parent::get($slug));
    }
}
