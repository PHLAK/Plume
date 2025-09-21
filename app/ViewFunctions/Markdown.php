<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use League\CommonMark\ConverterInterface;

class Markdown implements ViewFunction
{
    public string $name = 'markdown';

    public function __construct(
        private ConverterInterface $converter
    ) {}

    /** Convert markdown to HTML. */
    public function __invoke(string $markdown): string
    {
        return $this->converter->convert($markdown)->getContent();
    }
}
