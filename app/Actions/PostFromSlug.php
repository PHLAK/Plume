<?php

declare(strict_types=1);

namespace App\Actions;

use App\Config;
use App\Data\Post;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class PostFromSlug
{
    public function __construct(
        private Config $config,
    ) {}

    public function __invoke(string $slug): Post
    {
        $postPath = sprintf('%s/%s.md', $this->config->string('posts_path'), $slug);

        $document = YamlFrontMatter::parseFile($postPath);

        return Post::fromDocument($document);
    }
}
