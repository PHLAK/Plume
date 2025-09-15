<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Config;
use App\Data\Post;
use App\Posts;
use Illuminate\Support\Collection;
use Symfony\Contracts\Cache\CacheInterface;

class CachedPosts extends Posts
{
    public function __construct(
        private CacheInterface $cache,
        private Config $config,
    ) {
        parent::__construct($config);
    }

    public function all(): Collection
    {
        return $this->cache->get('all-posts', fn (): Collection => parent::all());
    }

    public function get(string $slug): Post
    {
        return $this->cache->get(sprintf('post|%s', $slug), fn (): Post => parent::get($slug));
    }
}
