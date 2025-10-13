<?php

declare(strict_types=1);

namespace App\Data;

use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

final readonly class Page
{
    public function __construct(
        public string $title,
        public string $body,
        public string $link,
        public int $weight = 0,
    ) {}

    public static function fromRenderedContent(RenderedContentWithFrontMatter $content): self
    {
        $frontMatter = $content->getFrontMatter();

        return new self(
            title: $frontMatter['title'],
            body: $content->getContent(),
            link: $frontMatter['link'] ?? $frontMatter['title'],
            weight: $frontMatter['weight'] ?? 0,
        );
    }
}
