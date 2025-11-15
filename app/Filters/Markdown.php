<?php

declare(strict_types=1);

namespace App\Filters;

use DI\Attribute\Inject;
use League\CommonMark\ConverterInterface;
use Twig\Markup;

class Markdown extends ViewFilter
{
    public string $name = 'markdown';

    #[Inject(ConverterInterface::class)]
    private ConverterInterface $converter;

    public function __invoke(string $markdown): Markup
    {
        return new Markup($this->converter->convert($markdown)->getContent(), 'UTF-8');
    }
}
