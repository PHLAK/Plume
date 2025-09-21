<?php

declare(strict_types=1);

namespace App\Data;

use App\Helpers\Str;
use App\Traits\ParsesMarkdown;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

final class Post
{
    use ParsesMarkdown;

    public readonly ?string $excerpt;
    public readonly CarbonInterface $published;

    /** @param string[] $tags */
    public function __construct(
        public string $title,
        public string $body,
        string|int $published,
        public ?string $author = null,
        public array $tags = [],
        public ?PostImage $image = null,
        public bool $draft = false,
    ) {
        $this->excerpt = $this->excerpt($body);
        $this->published = Carbon::parse($published);
    }

    public static function fromRenderedContent(RenderedContentWithFrontMatter $content): self
    {
        $frontMatter = $content->getFrontMatter();

        $image = ($frontMatter['image_url'] ?? false)
            ? new PostImage($frontMatter['image_url'], $frontMatter['image_caption'] ?? null)
            : null;

        return new self(
            title: $frontMatter['title'],
            body: $content->getContent(),
            published: $frontMatter['published'],
            author:  $frontMatter['author'] ?? null,
            tags: $frontMatter['tags'] ?? [],
            image: $image,
            draft: (bool) ($frontMatter['draft'] ?? false),
        );
    }

    private function body(string $body): string
    {
        return $this->parseMarkdown($body);
    }

    private function excerpt(string $body): ?string
    {
        if (! $this->hasExcerpt($body)) {
            return $this->body($body);
        }

        /** @var ?string $excerpt */
        ['excerpt' => $excerpt] = Str::match('/<excerpt>(?<excerpt>.+)<\/excerpt>/s', $body);

        return $excerpt ?? null;
    }

    private function hasExcerpt(string $body): bool
    {
        return mb_strstr($body, '<excerpt>') && mb_strstr($body, '</excerpt>');
    }
}
