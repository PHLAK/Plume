<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use App\Config;

class Scripts implements ViewFunction
{
    public string $name = 'scripts';

    public function __construct(
        private Config $config,
    ) {}

    /** Get the contents of the custom scripts file. */
    public function __invoke(): string
    {
        $scriptsFile = $this->config->string('scripts_file');

        if (! is_file($scriptsFile)) {
            return '';
        }

        return trim((string) file_get_contents($scriptsFile));
    }
}
