<?php

declare(strict_types=1);

namespace App\Functions;

use Twig\Markup;

class Css extends ViewFunction
{
    public string $name = 'css';

    public function __invoke(string $stylesheet): Markup
    {
        return new Markup(sprintf('<link rel="stylesheet" href="/theme/css/%s.css">', $stylesheet), 'UTF-8');
    }
}
