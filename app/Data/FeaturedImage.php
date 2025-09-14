<?php

declare(strict_types=1);

namespace App\Data;

use App\Traits\ParsesMarkdown;

final readonly class FeaturedImage
{
    use ParsesMarkdown;

    public ?string $caption;

    public function __construct(
        public string $url,
        ?string $caption = null,
    ) {
        $this->caption = $caption ? $this->parseMarkdown($caption) : null;
    }
}
