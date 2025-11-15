<?php

declare(strict_types=1);

namespace App\Factories;

use DI\Attribute\Inject;
use DI\Container;
use Symfony\Component\Console\Application;

class ConsoleAppFactory
{
    #[Inject('commands')]
    private array $commands;

    public function __construct(
        private Container $container,
    ) {}

    public function __invoke(): Application
    {
        $application = new Application;

        foreach ($this->commands as $command) {
            $application->add($this->container->make($command));
        }

        return $application;
    }
}
