<?php

declare(strict_types=1);

namespace App\Data;

final readonly class PostImage
{
    public function __construct(
        public string $url,
        public ?string $caption = null,
    ) {}

    public function forBaseUrl(string $baseUrl): self
    {
        if (parse_url($this->url, PHP_URL_HOST) !== null) {
            return $this;
        }

        return new self(sprintf('%s/%s', rtrim($baseUrl, '/'), ltrim($this->url, '/')), $this->caption);
    }
}
