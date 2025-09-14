<?php

declare(strict_types=1);

namespace App\Data;

use App\Helpers\Str;
use App\Traits\ParsesMarkdown;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Spatie\YamlFrontMatter\Document;

final class Post
{
    use ParsesMarkdown;

    public readonly string $body;
    public readonly ?string $excerpt;
    public readonly CarbonInterface $published;

    /** @param string[] $tags */
    public function __construct(
        public private(set) string $title,
        string $body,
        string|int $published,
        public private(set) array $tags = [],
        public private(set) ?FeaturedImage $featuredImage = null,
        public private(set) bool $draft = false,
    ) {
        $this->body = $this->body($body);
        $this->excerpt = $this->excerpt($body);
        $this->published = Carbon::parse($published);
    }

    public static function fromDocument(Document $document): self
    {
        $post = new self(
            title: $document->title,
            body: $document->body(),
            published: $document->published,
            tags: $document->matter('tags', []),
            draft: (bool) $document->matter('draft', false),
        );

        if ((bool) $document->featured_image_url) {
            $post->featuredImage(new FeaturedImage($document->featured_image_url, $document->featured_image_text));
        }

        return $post;
    }

    public function featuredImage(FeaturedImage $featuredImage): self
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    private function body(string $body): string
    {
        return $this->parseMarkdown((string) preg_replace('/<\/?excerpt>/', '', $body));
    }

    private function excerpt(string $body): ?string
    {
        if (! $this->hasExcerpt($body)) {
            return $this->body($body);
        }

        /** @var ?string $excerpt */
        ['excerpt' => $excerpt] = Str::match('/<excerpt>(?<excerpt>.+)<\/excerpt>/s', $body);

        return $excerpt ? $this->parseMarkdown($excerpt) : null;
    }

    private function hasExcerpt(string $body): bool
    {
        return mb_strstr($body, '<excerpt>') && mb_strstr($body, '</excerpt>');
    }
}
