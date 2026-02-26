<?php

declare(strict_types=1);

namespace App\Data;

use App\Helpers\Str;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

final class Post
{
    public readonly ?string $excerpt;

    /** @param string[] $tags */
    public function __construct(
        public string $title,
        public string $body,
        public CarbonInterface $published,
        public ?string $author = null,
        public array $tags = [],
        public ?PostImage $image = null,
        public ?string $canonical = null,
        public bool $draft = false,
    ) {
        $this->excerpt = $this->excerpt($body);
    }

    public static function fromRenderedContent(RenderedContentWithFrontMatter $content): self
    {
        /** @var array{title:string, link?:string, weight?:int} $frontMatter */
        $frontMatter = $content->getFrontMatter();

        return new self(
            title: $frontMatter['title'],
            body: $content->getContent(),
            published: Carbon::parse($frontMatter['published']),
            author:  $frontMatter['author'] ?? null,
            tags: $frontMatter['tags'] ?? [],
            image: ($frontMatter['image'] ?? false) ? new PostImage(...$frontMatter['image']) : null,
            canonical: $frontMatter['canonical'] ?? null,
            draft: (bool) ($frontMatter['draft'] ?? false),
        );
    }

    private function excerpt(string $body): ?string
    {
        if (! $this->hasExcerpt($body)) {
            return $this->body;
        }

        [$excerpt] = Str::extract('/<!-- excerpt -->(?:\s+)?(?<excerpt>.+)(?:\s+)?<!-- \/excerpt -->/s', $body);

        return $excerpt ?? null;
    }

    private function hasExcerpt(string $body): bool
    {
        return mb_strstr($body, '<!-- excerpt -->') && mb_strstr($body, '<!-- /excerpt -->');
    }
}
