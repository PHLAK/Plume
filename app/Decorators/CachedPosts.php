<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Data\Post;
use App\Posts;
use DI\Attribute\Inject;
use Illuminate\Support\Collection;
use Symfony\Contracts\Cache\CacheInterface;

class CachedPosts extends Posts
{
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    public function get(string $slug): Post
    {
        return $this->cache->get(sprintf('post|%s', $slug), fn (): Post => parent::get($slug));
    }

    public function all(): Collection
    {
        return $this->cache->get('all-posts', fn (): Collection => parent::all());
    }

    public function withTag(string $tag): Collection
    {
        return $this->cache->get(sprintf('tag|%s', $tag), fn (): Collection => parent::withTag($tag));
    }
}
