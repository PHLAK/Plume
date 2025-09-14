<?php

declare(strict_types=1);

namespace App\Traits;

use League\CommonMark\GithubFlavoredMarkdownConverter;

trait ParsesMarkdown
{
    private function parseMarkdown(string $string): string
    {
        return (string) new GithubFlavoredMarkdownConverter()->convert($string);
    }
}
