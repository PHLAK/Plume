<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use DI\Attribute\Inject;
use DOMDocument;
use Twig\Markup;

class Svg implements ViewFunction
{
    public string $name = 'svg';

    #[Inject('icons_path')]
    private string $iconsPath;

    public function __invoke(string $icon, $classes = []): Markup
    {
        $svgPath = sprintf('%s/%s.svg', $this->iconsPath, $icon);

        $dom = new DOMDocument;
        $dom->loadXML((string) file_get_contents($svgPath));

        foreach ($dom->getElementsByTagName('svg') as $element) {
            $element->setAttribute('class', implode(' ', $classes));
        }

        return new Markup($dom->saveXML(), 'UTF-8');
    }
}
