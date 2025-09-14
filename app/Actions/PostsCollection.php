<?php

declare(strict_types=1);

namespace App\Actions;

use App\Config;
use App\Data\Post;
use App\Helpers\Str;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class PostsCollection
{
    public function __construct(
        private Config $config,
    ) {}

    /** @return Collection<string, Post> */
    public function __invoke(): Collection
    {
        /** @var Collection<int, string> $paths */
        $paths = new Collection(glob($this->config->string('posts_path') . '/*.md') ?: []);

        return $paths->mapWithKeys(function (string $path): array {
            ['slug' => $slug] = Str::match(sprintf('#^%s/(?<slug>.+).md$#', preg_quote($this->config->string('posts_path'), '#')), $path);

            return [$slug => Post::fromDocument(YamlFrontMatter::parseFile($path))];
        })->reject(
            fn (Post $post): bool => $post->published->isFuture()
        )->sortByDesc(
            fn (Post $post): CarbonInterface => $post->published
        );
    }
}
