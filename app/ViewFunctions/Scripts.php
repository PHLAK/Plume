<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use DI\Attribute\Inject;

class Scripts implements ViewFunction
{
    public string $name = 'scripts';

    #[Inject('scripts_file')]
    private string $scriptsFile;

    /** Get the contents of the custom scripts file. */
    public function __invoke(): string
    {
        if (! is_file($this->scriptsFile)) {
            return '';
        }

        return trim((string) file_get_contents($this->scriptsFile));
    }
}
