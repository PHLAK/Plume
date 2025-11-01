<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use DI\Container;
use DI\NotFoundException;

class Config implements ViewFunction
{
    public string $name = 'config';

    public function __construct(
        private Container $container,
    ) {}

    public function __invoke(string $key, mixed $default = null): mixed
    {
        try {
            return $this->container->get($key);
        } catch (NotFoundException) {
            return $default;
        }
    }
}
