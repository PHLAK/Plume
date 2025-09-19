<?php

declare(strict_types=1);

namespace App\Decorators;

use App\Config;
use App\Data\Post;
use App\Posts;
use Illuminate\Support\Collection;
use League\CommonMark\ConverterInterface;
use Symfony\Contracts\Cache\CacheInterface;

class CachedPosts extends Posts
{
    public function __construct(
        private CacheInterface $cache,
        private Config $config,
        private ConverterInterface $converter,
    ) {
        parent::__construct($config, $converter);
    }

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
