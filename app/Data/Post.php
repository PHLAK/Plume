<?php

declare(strict_types=1);

namespace App\Data;

use App\Helpers\Str;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

final class Post
{
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
    ) {}

    public static function fromRenderedContent(RenderedContentWithFrontMatter $content): self
    {
        /** @var array{title:string, published:string|int, author?:string, tags?: list<string>, canonical?: string, draft?: bool} $frontMatter */
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

    public function bodyForIndex(): string
    {
        return preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($this->body)));
    }

    public function excerpt(): string
    {
        if (! $this->hasExcerpt()) {
            return $this->body;
        }

        [$excerpt] = Str::extract('/<!-- excerpt -->(?:\s+)?(?<excerpt>.+)(?:\s+)?<!-- \/excerpt -->/s', $this->body);

        return $excerpt ?? $this->body;
    }

    private function hasExcerpt(): bool
    {
        return mb_strstr($this->body, '<!-- excerpt -->') && mb_strstr($this->body, '<!-- /excerpt -->');
    }
}
