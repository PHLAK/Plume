<?php

declare(strict_types=1);

namespace App;

use App\Data\Post;
use App\Exceptions\PostNotFoundException;
use App\Helpers\Str;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use League\CommonMark\ConverterInterface;

class Posts
{
    public function __construct(
        private Config $config,
        private ConverterInterface $converter,
    ) {}

    public function get(string $slug): Data\Post
    {
        $postPath = sprintf('%s/%s.md', $this->config->string('posts_path'), $slug);

        if (! is_readable($postPath)) {
            throw new PostNotFoundException;
        }

        if (($contents = file_get_contents($postPath)) === false) {
            throw new PostNotFoundException;
        }

        return Post::fromRenderedContent($this->converter->convert($contents));
    }

    /** @return Collection<int, Post> */
    public function all(): Collection
    {
        /** @var Collection<int, string> $paths */
        $paths = new Collection(glob($this->config->string('posts_path') . '/*.md') ?: []);

        return $paths->mapWithKeys(function (string $path): array {
            [$slug] = Str::extract(sprintf('#^%s/(?<slug>.+).md$#', preg_quote($this->config->string('posts_path'), '#')), $path);

            $content = $this->converter->convert(file_get_contents($path));

            return [$slug => Data\Post::fromRenderedContent($content)];
        })->reject(
            fn (Post $post): bool => $post->draft || $post->published->isFuture()
        )->sortByDesc(
            fn (Post $post): CarbonInterface => $post->published
        );
    }

    public function withTag(string $tag): Collection
    {
        return $this->all()->filter(
            fn (Post $post): bool => in_array($tag, $post->tags)
        );
    }
}
