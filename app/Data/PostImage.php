<?php

declare(strict_types=1);

namespace App\Data;

final readonly class PostImage
{
    public function __construct(
        public string $url,
        public ?string $caption = null,
    ) {}
}
