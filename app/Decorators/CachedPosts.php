<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Data\Post;
use App\Posts;
use DI\Attribute\Inject;
use Illuminate\Support\LazyCollection;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class CachedPosts extends Posts
{
    /** @var AbstractAdapter $cache */
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
