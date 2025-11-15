<?php

declare(strict_types=1);

namespace App\Functions;

use DI\Attribute\Inject;
use Twig\Markup;

class Scripts extends ViewFunction
{
    public string $name = 'scripts';

    #[Inject('scripts_file')]
    private string $scriptsFile;

    /** Get the contents of the custom scripts file. */
    public function __invoke(): Markup
    {
        if (! is_file($this->scriptsFile)) {
            return new Markup('', 'UTF-8');
        }

        $scripts = trim((string) file_get_contents($this->scriptsFile));

        return new Markup($scripts, 'UTF-8');
    }
}
