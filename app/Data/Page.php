<?php

declare(strict_types=1);

namespace App\Data;

use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

final readonly class Page
{
    public function __construct(
        public string $title,
        public string $link,
        public string $body,
        public int $weight = 0,
    ) {}

    public static function fromRenderedContent(RenderedContentWithFrontMatter $content): self
    {
        /** @var array{title:string, link?:string, weight?:int} $frontMatter */
        $frontMatter = $content->getFrontMatter();

        return new self(
            title: $frontMatter['title'],
            link: $frontMatter['link'] ?? $frontMatter['title'],
            body: $content->getContent(),
            weight: $frontMatter['weight'] ?? 0,
        );
    }
}
