<?php

declare(strict_types=1);

namespace App\Commands;

use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand('publish', description: 'Publish all posts and pages and purge caches')]
class Publish extends BaseCommand
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    public function __invoke(
        Application $application,
    ): int {
        if ($this->cache instanceof ArrayAdapter) {
            $this->error("This command has no affect when using the 'array' cache driver");

            return self::FAILURE;
        }

        $application->doRun(new StringInput('publish:posts'), $this->output);
        $this->newLine();
        $application->doRun(new StringInput('publish:pages'), $this->output);

        return Command::SUCCESS;
    }
}
