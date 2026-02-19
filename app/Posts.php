<?php

declare(strict_types=1);

namespace App;

use App\Data\Post;
use App\Exceptions\NotFoundException;
use DI\Attribute\Inject;
use GlobIterator;
use Illuminate\Support\LazyCollection;
use League\CommonMark\ConverterInterface;
use SplFileInfo;

class Posts
{
    #[Inject('posts_path')]
    private string $postsPath;

    #[Inject(ConverterInterface::class)]
    private ConverterInterface $converter;

    /** @throws NotFoundException */
    public function get(string $slug): Data\Post
    {
        $post = new SplFileInfo(sprintf('%s/%s.md', $this->postsPath, $slug));

        if (! $post->isReadable()) {
            throw new NotFoundException;
        }

        if (($contents = file_get_contents($post->getRealPath())) === false) {
            throw new NotFoundException;
        }

        return Post::fromRenderedContent($this->converter->convert($contents));
    }

    /** @return LazyCollection<int, Post> */
    public function all(): LazyCollection
    {
        $posts = new GlobIterator($this->postsPath . '/*.md');

        return new LazyCollection(function () use ($posts) {
            foreach ($posts as $post) {
                /** @var SplFileInfo $post */
                $slug = $post->getBasename('.md');

                yield $slug => $this->get($slug);
            }
        })->reject(
            fn (Post $post): bool => $post->draft || $post->published->isFuture()
        )->sortByDesc('published');
    }

    /** @return LazyCollection<int, Post> */
    public function byAuthor(string $author): LazyCollection
    {
        return $this->all()->filter(
            fn (Post $post): bool => $post->author === $author
        );
    }

    /** @return LazyCollection<int, Post> */
    public function withTag(string $tag): LazyCollection
    {
        return $this->all()->filter(
            fn (Post $post): bool => in_array($tag, $post->tags)
        );
    }
}
