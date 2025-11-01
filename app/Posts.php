<?php

declare(strict_types=1);

namespace App;

use App\Data\Post;
use App\Exceptions\NotFoundException;
use App\Helpers\Str;
use DI\Attribute\Inject;
use Illuminate\Support\Collection;
use League\CommonMark\ConverterInterface;

class Posts
{
    #[Inject('posts_path')]
    private string $postsPath;

    public function __construct(
        private ConverterInterface $converter,
    ) {}

    public function get(string $slug): Data\Post
    {
        $postPath = sprintf('%s/%s.md', $this->postsPath, $slug);

        if (! is_readable($postPath)) {
            throw new NotFoundException;
        }

        if (($contents = file_get_contents($postPath)) === false) {
            throw new NotFoundException;
        }

        return Post::fromRenderedContent($this->converter->convert($contents));
    }

    /** @return Collection<int, Post> */
    public function all(): Collection
    {
        /** @var Collection<int, string> $paths */
        $paths = new Collection(glob($this->postsPath . '/*.md') ?: []);

        return $paths->mapWithKeys(function (string $path): array {
            [$slug] = Str::extract(sprintf('#^%s/(?<slug>.+).md$#', preg_quote($this->postsPath, '#')), $path);

            $content = $this->converter->convert(file_get_contents($path));

            return [$slug => Data\Post::fromRenderedContent($content)];
        })->reject(
            fn (Post $post): bool => $post->draft || $post->published->isFuture()
        )->sortByDesc('published');
    }

    public function byAuthor(string $author): Collection
    {
        return $this->all()->filter(
            fn (Post $post): bool => $post->author === $author
        );
    }

    public function withTag(string $tag): Collection
    {
        return $this->all()->filter(
            fn (Post $post): bool => in_array($tag, $post->tags)
        );
    }
}
