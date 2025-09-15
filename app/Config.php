<?php

declare(strict_types=1);

namespace App;

use DI\Container;
use DI\NotFoundException;
use UnexpectedValueException;

class Config
{
    public function __construct(
        private Container $container
    ) {}

    public function get(string $key, mixed $default = null): mixed
    {
        try {
            $value = $this->container->get($key);
        } catch (NotFoundException) {
            return $default;
        }

        if (! is_string($value)) {
            return $value;
        }

        switch (strtolower($value)) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'null':
                return null;
        }

        return preg_replace('/^"(.*)"$/', '$1', $value);
    }

    /** @return array<mixed, mixed> */
    public function array(string $key, mixed $default = null): array
    {
        $value = $this->get($key, $default);

        if (! is_array($value)) {
            throw new UnexpectedValueException(sprintf('Configuration value for key [%s] must be an array, %s given.', $key, gettype($value)));
        }

        return $value;
    }

    /** @return array<mixed, mixed> */
    public function boolean(string $key, mixed $default = null): bool
    {
        $value = $this->get($key, $default);

        /** @var bool|null $filtered */
        $filtered = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($filtered === null) {
            throw new UnexpectedValueException(sprintf('Configuration value for key [%s] must be an array, %s given.', $key, gettype($value)));
        }

        return $filtered;
    }

    public function integer(string $key, mixed $default = null): int
    {
        $value = $this->get($key, $default);

        if (! is_numeric($value)) {
            throw new UnexpectedValueException(sprintf('Configuration value for key [%s] must be an integer, %s given.', $key, gettype($value)));
        }

        return (int) $value;
    }

    public function string(string $key, mixed $default = null): string
    {
        $value = $this->get($key, $default);

        if (! is_string($value)) {
            throw new UnexpectedValueException(sprintf('Configuration value for key [%s] must be a string, %s given.', $key, gettype($value)));
        }

        return $value;
    }
}
