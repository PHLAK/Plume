<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use DI\Attribute\Inject;
use League\CommonMark\ConverterInterface;

class Markdown implements ViewFunction
{
    public string $name = 'markdown';

    #[Inject(ConverterInterface::class)]
    private ConverterInterface $converter;

    public function __invoke(string $markdown): string
    {
        return $this->converter->convert($markdown)->getContent();
    }
}
