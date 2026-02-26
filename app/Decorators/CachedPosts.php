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
    /** @var AbstractAdapter */
    private CacheInterface $cache;

    /** @param AbstractAdapter $cache */
    public function __construct(
        #[Inject(CacheInterface::class)] CacheInterface $cache,
    ) {
        $this->cache = $cache->withSubNamespace('posts');
    }

    public function get(string $slug): Post
    {
        return $this->cache->get(hash('xxh128', $slug), fn (): Post => parent::get($slug));
    }

    public function all(): LazyCollection
    {
        return $this->cache->get('all', fn (): LazyCollection => parent::all());
    }

    public function byAuthor(string $author): LazyCollection
    {
        return $this->cache->withSubNamespace('by-author')->get($author, fn (): LazyCollection => parent::byAuthor($author));
    }

    public function withTag(string $tag): LazyCollection
    {
        return $this->cache->withSubNamespace('with-tag')->get($tag, fn (): LazyCollection => parent::withTag($tag));
    }
}
