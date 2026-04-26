<?php

declare(strict_types=1);

namespace App\Functions;

use Illuminate\Support\Collection;
use Twig\Markup;

class Js extends ViewFunction
{
    public string $name = 'js';

    /** @param array<int|string, string> $modifiers */
    public function __invoke(string $script, array $modifiers = ['type' => 'module']): Markup
    {
        return new Markup(sprintf('<script src="/theme/js/%s.js" %s></script>', $script, $this->modifiers($modifiers)), 'UTF-8');
    }

    /** @param array<int|string, string> $modifiers */
    private function modifiers(array $modifiers): string
    {
        return new Collection($modifiers)->map(
            fn (string $value, int|string $key): string => is_int($key) ? $value : sprintf('%s="%s"', $key, $value)
        )->join(' ');
    }
}
