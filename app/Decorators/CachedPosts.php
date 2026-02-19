<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Data\Post;
use App\Posts;
use DI\Attribute\Inject;
use Illuminate\Support\LazyCollection;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\NamespacedPoolInterface;

class CachedPosts extends Posts
{
    /** @var CacheInterface&NamespacedPoolInterface */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    public function get(string $slug): Post
    {
        return $this->cache->withSubNamespace('posts')->get($slug, fn (): Post => parent::get($slug));
    }

    public function all(): LazyCollection
    {
        return $this->cache->get('all-posts', fn (): LazyCollection => parent::all());
    }

    public function withTag(string $tag): LazyCollection
    {
        return $this->cache->withSubNamespace('posts-with-tag')->get($tag, fn (): LazyCollection => parent::withTag($tag));
    }
}
