<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use App\Config as AppConfig;

class Config implements ViewFunction
{
    public string $name = 'config';

    public function __construct(
        private AppConfig $config
    ) {}

    /**
     * Retrieve an item from the config.
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function __invoke(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }
}
